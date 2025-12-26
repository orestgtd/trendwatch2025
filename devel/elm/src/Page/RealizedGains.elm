module Page.RealizedGains exposing
    ( Model
    , Msg
    , init
    , update
    , view
    , fetchRealizedGains
    )

import Html exposing (Html, button, div, text, table, thead, tbody, tr, th, td, h1, span)
import Html.Events exposing (onClick)
import Html.Attributes exposing (style)
import Json.Decode as Decode exposing (Decoder)
import Http
import Util.Http exposing (httpErrorToString)

-- MODEL


type alias Model =
    { loading : Bool
    , realizedGains : List RealizedGain
    , error : Maybe String
    }


init : Model
init =
    { loading = False
    , realizedGains = []
    , error = Nothing
    }


-- MESSAGES


type Msg
    = GotResponse (Result Http.Error RealizedGainsResponse)
    | FetchRealizedGains


type alias RealizedGainsResponse =
    { data : List RealizedGain
    , count : Int
    }


type alias RealizedGain =
    { id : Int
    , securityNumber : String
    , tradeNumber : String
    , baseQuantity : Int
    , tradeQuantity : Int
    , unitType : String
    , costAmount : Float
    , proceedsAmount : Float
    }

-- UPDATE


update : Msg -> Model -> ( Model, Cmd Msg )
update msg model =
    case msg of
        FetchRealizedGains ->
            ( { model | loading = True, error = Nothing }
            , getRealizedGains
            )

        GotResponse result ->
            case result of
                Ok response ->
                    ( { model
                        | realizedGains = response.data
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


realizedGainsResponseDecoder : Decoder RealizedGainsResponse
realizedGainsResponseDecoder =
    Decode.map2 RealizedGainsResponse
        (Decode.field "data" (Decode.list realizedGainDecoder))
        (Decode.field "count" Decode.int)

realizedGainDecoder : Decoder RealizedGain
realizedGainDecoder =
    Decode.map8 RealizedGain
        (Decode.field "id" Decode.int)
        (Decode.field "security_number" Decode.string)
        (Decode.field "trade_number" Decode.string)
        (Decode.field "base_quantity" Decode.int)
        (Decode.field "trade_quantity" Decode.int)
        (Decode.field "unit_type" Decode.string)
        (Decode.field "cost" Decode.float)
        (Decode.field "proceeds" Decode.float)

-- HTTP


getRealizedGains : Cmd Msg
getRealizedGains =
    Http.get
        { url = "/api/realized_gains"
        , expect = Http.expectJson GotResponse realizedGainsResponseDecoder
        }


-- PUBLIC ACTION


fetchRealizedGains : Msg
fetchRealizedGains =
    FetchRealizedGains


-- CALCULATIONS


calculateTotals :
    List RealizedGain
    -> { totalCost : Float, totalProceeds : Float, realizedPnL : Float }
calculateTotals gains =
    let
        totalCost =
            List.sum (List.map .costAmount gains)

        totalProceeds =
            List.sum (List.map .proceedsAmount gains)

        realizedPnL =
            totalProceeds - totalCost
    in
    { totalCost = totalCost
    , totalProceeds = totalProceeds
    , realizedPnL = realizedPnL
    }


-- VIEW


view : Model -> Html Msg
view model =
    div [ style "padding" "2rem" ]
        [ h1
            [ style "font-size" "2rem"
            , style "font-weight" "700"
            , style "margin-bottom" "1.5rem"
            ]
            [ text "Realized Gains" ]
        , button
            [ style "background-color" "#3b82f6"
            , style "color" "white"
            , style "padding" "0.75rem 1.5rem"
            , style "border-radius" "0.5rem"
            , style "border" "none"
            , style "cursor" "pointer"
            , style "margin-bottom" "2rem"
            , onClick FetchRealizedGains
            ]
            [ text "Fetch Realized Gains" ]
        , if model.loading then
            div [ style "padding" "2rem", style "text-align" "center" ]
                [ text "Loading..." ]

          else
            case model.error of
                Just errorMsg ->
                    div
                        [ style "background-color" "#fee2e2"
                        , style "color" "#991b1b"
                        , style "padding" "1rem"
                        , style "border-radius" "0.5rem"
                        ]
                        [ text ("Error: " ++ errorMsg) ]

                Nothing ->
                    viewRealizedGains model.realizedGains
        ]


viewRealizedGains : List RealizedGain -> Html Msg
viewRealizedGains gains =
    if List.isEmpty gains then
        div
            [ style "padding" "3rem"
            , style "text-align" "center"
            , style "color" "#6b7280"
            ]
            [ text "No realized gains available. Click 'Fetch Realized Gains' to load data." ]

    else
        div []
            [ viewSummaryCards (calculateTotals gains)
            , table
                [ style "width" "100%"
                , style "border-collapse" "collapse"
                , style "background-color" "white"
                , style "box-shadow" "0 1px 3px rgba(0,0,0,0.1)"
                , style "border-radius" "0.5rem"
                ]
                [ thead []
                    [ tr []
                        [ th [ headerStyle ] [ text "ID" ]
                        , th [ headerStyle ] [ text "Security" ]
                        , th [ headerStyle ] [ text "Trade" ]
                        , th [ headerStyle, rightAlign ] [ text "Base Qty" ]
                        , th [ headerStyle, rightAlign ] [ text "Trade Qty" ]
                        , th [ headerStyle ] [ text "Unit" ]
                        , th [ headerStyle, rightAlign ] [ text "Cost" ]
                        , th [ headerStyle, rightAlign ] [ text "Proceeds" ]
                        ]
                    ]
                , tbody [] (List.map viewRealizedGain gains)
                ]
            ]


headerStyle : Html.Attribute msg
headerStyle =
    style "padding" "1rem"


rightAlign : Html.Attribute msg
rightAlign =
    style "text-align" "right"


viewSummaryCards :
    { totalCost : Float, totalProceeds : Float, realizedPnL : Float }
    -> Html Msg
viewSummaryCards totals =
    div
        [ style "display" "grid"
        , style "grid-template-columns" "repeat(3, 1fr)"
        , style "gap" "1.5rem"
        , style "margin-bottom" "2rem"
        ]
        [ viewCard "Total Cost" totals.totalCost "#3b82f6"
        , viewCard "Total Proceeds" totals.totalProceeds "#10b981"
        , viewCard "Realized P/L" totals.realizedPnL
            (if totals.realizedPnL >= 0 then "#3b82f6" else "#ef4444")
        ]


viewCard : String -> Float -> String -> Html Msg
viewCard label amount color =
    div
        [ style "background-color" "white"
        , style "border-left" ("4px solid " ++ color)
        , style "padding" "1.5rem"
        , style "border-radius" "0.5rem"
        , style "box-shadow" "0 1px 3px rgba(0,0,0,0.1)"
        ]
        [ div
            [ style "color" "#6b7280"
            , style "font-size" "0.875rem"
            , style "font-weight" "600"
            , style "margin-bottom" "0.5rem"
            ]
            [ text label ]
        , div
            [ style "color" "#1f2937"
            , style "font-size" "1.875rem"
            , style "font-weight" "700"
            ]
            [ text (formatCurrency amount) ]
        ]


viewRealizedGain : RealizedGain -> Html Msg
viewRealizedGain gain =
    tr [ style "border-bottom" "1px solid #e5e7eb" ]
        [ td [ style "padding" "1rem" ] [ text (String.fromInt gain.id) ]
        , td [ style "padding" "1rem", style "font-weight" "600" ] [ text gain.securityNumber ]
        , td [ style "padding" "1rem" ] [ text gain.tradeNumber ]
        , td [ style "padding" "1rem", rightAlign ] [ text (String.fromInt gain.baseQuantity) ]
        , td [ style "padding" "1rem", rightAlign ] [ text (String.fromInt gain.tradeQuantity) ]
        , td [ style "padding" "1rem", style "text-align" "center"]
            [ span
                [ style "background-color" (if gain.unitType == "SHARES" then "#d1fae5" else "#fee2e2")
                , style "color" (if gain.unitType == "SHARES" then "#065f46" else "#991b1b")
                , style "padding" "0.25rem 0.75rem"
                , style "border-radius" "9999px"
                , style "font-size" "0.875rem"
                , style "font-weight" "600"
                ]
                [ text gain.unitType ]
            ]
        , td [ style "padding" "1rem", rightAlign ] [ text (formatCurrency gain.costAmount) ]
        , td [ style "padding" "1rem", rightAlign ] [ text (formatCurrency gain.proceedsAmount) ]
        ]


formatCurrency : Float -> String
formatCurrency amount =
    let
        rounded =
            String.fromFloat (toFloat (round (amount * 100)) / 100)
    in
    "$" ++ rounded
