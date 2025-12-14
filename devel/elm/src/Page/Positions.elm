module Page.Positions exposing (Model, Msg, init, update, view, fetchPositions)

import Html exposing (Html, button, div, text, table, thead, tbody, tr, th, td, h1, span)
import Html.Events exposing (onClick)
import Json.Decode as Decode exposing (Decoder)
import Http
import Util.Http exposing (httpErrorToString)
import Html.Attributes exposing (class, style)

-- MODEL

type alias Model =
    { loading : Bool
    , positions : List Position
    , error : Maybe String
    }

init =
    { loading = False
    , positions = []
    , error = Nothing
    }

-- MESSAGES

type Msg
    = GotResponse (Result Http.Error PositionsResponse)
    | FetchPositions

type alias PositionsResponse =
    { data : List Position
    , count : Int
    }

type alias Position =
    { id : Int
    , securityNumber : String
    , positionType : String
    , positionQuantity : Int
    , totalCost : Float
    , totalProceeds : Float
    }

-- UPDATE

update : Msg -> Model -> ( Model, Cmd Msg )
update msg model =
    case msg of
        FetchPositions ->
            ( { model | loading = True, error = Nothing }, getPositions )

        GotResponse result ->
            case result of
                Ok response ->
                    ( { model 
                        | positions = response.data
                        , loading = False
                        , error = Nothing
                      }
                    , Cmd.none 
                    )

                Err error ->
                    ( { model 
                        | error = Just (httpErrorToString error)
                        , loading = False 
                      }
                    , Cmd.none 
                    )

-- JSON DECODERS

positionsResponseDecoder : Decoder PositionsResponse
positionsResponseDecoder =
    Decode.map2 PositionsResponse
        (Decode.field "data" (Decode.list positionDecoder))
        (Decode.field "count" Decode.int)
    
positionDecoder : Decoder Position
positionDecoder =
    Decode.map6 Position
        (Decode.field "id" Decode.int)
        (Decode.field "security_number" Decode.string)
        (Decode.field "position_type" Decode.string)
        (Decode.field "position_quantity" Decode.int)
        (Decode.field "total_cost" Decode.float)
        (Decode.field "total_proceeds" Decode.float)

-- HTTP

getPositions : Cmd Msg
getPositions =
    Http.get
        { url = "/api/positions" -- change to full URL if needed
        , expect = Http.expectJson GotResponse positionsResponseDecoder
        }


-- PUBLIC ACTION

fetchPositions : Msg
fetchPositions =
    FetchPositions

-- CALCULATIONS

calculateTotals : List Position -> { totalCost : Float, totalProceeds : Float, netPosition : Float }
calculateTotals positions =
    let
        totalCost = List.sum (List.map .totalCost positions)
        totalProceeds = List.sum (List.map .totalProceeds positions)
        netPosition = totalCost - totalProceeds
    in
    { totalCost = totalCost
    , totalProceeds = totalProceeds
    , netPosition = netPosition
    }

-- VIEW

view : Model -> Html Msg
view model =
    div [ style "padding" "2rem" ]
        [ h1 [ style "font-size" "2rem", style "font-weight" "700", style "margin-bottom" "1.5rem" ] 
            [ text "Positions" ]
        , button [ style "background-color" "#3b82f6"
                 , style "color" "white"
                 , style "padding" "0.75rem 1.5rem"
                 , style "border-radius" "0.5rem"
                 , style "border" "none"
                 , style "cursor" "pointer"
                 , style "margin-bottom" "2rem"
                 , onClick FetchPositions 
                 ] 
            [ text "Fetch Positions" ]
        , if model.loading then
            div [ style "padding" "2rem", style "text-align" "center" ] [ text "Loading..." ]
          else
            case model.error of
                Just errorMsg ->
                    div [ style "background-color" "#fee2e2"
                        , style "color" "#991b1b"
                        , style "padding" "1rem"
                        , style "border-radius" "0.5rem"
                        ] 
                        [ text ("Error: " ++ errorMsg) ]
                
                Nothing ->
                    viewPositions model.positions
        ]

viewPositions : List Position -> Html Msg
viewPositions positions =
    if List.isEmpty positions then
        div [ style "padding" "3rem", style "text-align" "center", style "color" "#6b7280" ] 
            [ text "No positions available. Click 'Fetch Positions' to load data." ]
    else
        div []
            [ viewSummaryCards (calculateTotals positions)
            , table [ style "width" "100%"
                    , style "border-collapse" "collapse"
                    , style "background-color" "white"
                    , style "box-shadow" "0 1px 3px rgba(0,0,0,0.1)"
                    , style "border-radius" "0.5rem"
                    ]
                [ thead []
                    [ tr []
                        [ th [ style "padding" "1rem", style "text-align" "left", style "background-color" "#f9fafb" ] [ text "ID" ]
                        , th [ style "padding" "1rem", style "text-align" "left", style "background-color" "#f9fafb" ] [ text "Security" ]
                        , th [ style "padding" "1rem", style "text-align" "left", style "background-color" "#f9fafb" ] [ text "Type" ]
                        , th [ style "padding" "1rem", style "text-align" "right", style "background-color" "#f9fafb" ] [ text "Quantity" ]
                        , th [ style "padding" "1rem", style "text-align" "right", style "background-color" "#f9fafb" ] [ text "Total Cost" ]
                        , th [ style "padding" "1rem", style "text-align" "right", style "background-color" "#f9fafb" ] [ text "Total Proceeds" ]
                        ]
                    ]
                , tbody [] (List.map viewPosition positions)
                ]
            ]

viewSummaryCards : { totalCost : Float, totalProceeds : Float, netPosition : Float } -> Html Msg
viewSummaryCards totals =
    div [ style "display" "grid"
        , style "grid-template-columns" "repeat(3, 1fr)"
        , style "gap" "1.5rem"
        , style "margin-bottom" "2rem"
        ]
        [ viewCard "Total Cost" totals.totalCost "#3b82f6"
        , viewCard "Total Proceeds" totals.totalProceeds "#10b981"
        , viewCard "Net Position" totals.netPosition (if totals.netPosition >= 0 then "#3b82f6" else "#ef4444")
        ]

viewCard : String -> Float -> String -> Html Msg
viewCard label amount color =
    div [ style "background-color" "white"
        , style "border-left" ("4px solid " ++ color)
        , style "padding" "1.5rem"
        , style "border-radius" "0.5rem"
        , style "box-shadow" "0 1px 3px rgba(0,0,0,0.1)"
        ]
        [ div [ style "color" "#6b7280"
              , style "font-size" "0.875rem"
              , style "font-weight" "600"
              , style "margin-bottom" "0.5rem"
              ] 
              [ text label ]
        , div [ style "color" "#1f2937"
              , style "font-size" "1.875rem"
              , style "font-weight" "700"
              ] 
              [ text (formatCurrency amount) ]
        ]

viewPosition : Position -> Html Msg
viewPosition position =
    tr [ style "border-bottom" "1px solid #e5e7eb" ]
        [ td [ style "padding" "1rem" ] [ text (String.fromInt position.id) ]
        , td [ style "padding" "1rem", style "font-weight" "600" ] [ text position.securityNumber ]
        , td [ style "padding" "1rem" ] 
            [ span [ style "background-color" (if position.positionType == "LONG" then "#dbeafe" else "#fee2e2")
                   , style "color" (if position.positionType == "LONG" then "#1e40af" else "#991b1b")
                   , style "padding" "0.25rem 0.75rem"
                   , style "border-radius" "9999px"
                   , style "font-size" "0.875rem"
                   , style "font-weight" "600"
                   ] 
                [ text position.positionType ] 
            ]
        , td [ style "padding" "1rem", style "text-align" "right" ] [ text (String.fromInt position.positionQuantity) ]
        , td [ style "padding" "1rem", style "text-align" "right" ] [ text (formatCurrency position.totalCost) ]
        , td [ style "padding" "1rem", style "text-align" "right" ] [ text (formatCurrency position.totalProceeds) ]
        ]

formatCurrency : Float -> String
formatCurrency amount =
    let
        rounded = String.fromFloat (toFloat (round (amount * 100)) / 100)
    in
    "$" ++ rounded
