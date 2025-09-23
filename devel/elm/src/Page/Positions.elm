module Page.Positions exposing (Model, Msg, init, update, view, fetchPositions)

import Html exposing (Html, button, div, text)
import Html.Events exposing (onClick)
import Http
import Util.Http exposing (httpErrorToString)

-- MODEL

type alias Model =
    { loading : Bool
    , output : String
    }

init : Model
init =
    { loading = False
    , output = ""
    }

-- MESSAGES

type Msg
    = GotResponse (Result Http.Error String)
    | FetchPositions


-- UPDATE

update : Msg -> Model -> ( Model, Cmd Msg )
update msg model =
    case msg of
        FetchPositions ->
            ( { model | loading = True }, getPositions )

        GotResponse result ->
            case result of
                Ok response ->
                    ( { model | output = response, loading = False }, Cmd.none )

                Err error ->
                    ( { model | output = httpErrorToString error, loading = False }, Cmd.none )


-- HTTP

getPositions : Cmd Msg
getPositions =
    Http.get
        { url = "/api/positions" -- change to full URL if needed
        , expect = Http.expectString GotResponse
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
            div [] [ text model.output ]
        ]
