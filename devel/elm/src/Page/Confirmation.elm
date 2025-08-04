module Page.Confirmation exposing (Model, Msg, init, update, view)

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
    = NoOp
    | GotResponse (Result Http.Error String)


-- UPDATE

update : Msg -> Model -> (Model, Cmd Msg)
update msg model =
    case msg of
        NoOp ->
            (model, Cmd.none)

        GotResponse result ->
            case result of
                Ok response ->
                    ( { model | output = response, loading = False }, Cmd.none )

                Err error ->
                    ( { model | output = httpErrorToString error, loading = False }, Cmd.none )


-- VIEW

view : Model -> Html Msg
view model =
    div []
        [ text "Confirmation Page content here" ]
