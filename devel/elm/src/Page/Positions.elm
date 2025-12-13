module Page.Positions exposing (Model, Msg, init, update, view, fetchPositions)

import Html exposing (Html, button, div, text)
import Html.Events exposing (onClick)
import Json.Decode as Decode exposing (Decoder)
import Http
import Util.Http exposing (httpErrorToString)

-- MODEL

type alias Model =
    { loading : Bool
    , message : String
    , positions : List Position
    }

init : Model
init =
    { loading = False
    , message = ""
    , positions = []
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
            ( { model | loading = True }, getPositions )

        GotResponse result ->
            case result of
                Ok response ->
                    ( { model 
                        | positions = response.data
                        , message = "Loaded " ++ String.fromInt response.count ++ " positions"
                        , loading = False 
                    }
                    , Cmd.none 
                    )

                Err error ->
                    ( { model | message = httpErrorToString error, loading = False }, Cmd.none )

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


-- VIEW

view : Model -> Html Msg
view model =
    div []
        [ button [ onClick FetchPositions ] [ text "Fetch Positions" ]
        , if model.loading then
            div [] [ text "Loading..." ]
          else
            div [] 
                [ div [] [ text model.message ]
                , viewPositions model.positions
                ]
        ]

viewPositions : List Position -> Html Msg
viewPositions positions =
    div [] (List.map viewPosition positions)

viewPosition : Position -> Html Msg
viewPosition position =
    div []
        [ text (position.securityNumber ++ " - " ++ position.positionType ++ " - Qty: " ++ String.fromInt position.positionQuantity)
        ]
