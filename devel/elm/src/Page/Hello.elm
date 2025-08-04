module Page.Hello exposing (Model, Msg, init, update, view)

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
    = ClickHello
    | GotResponse (Result Http.Error String)


-- UPDATE

update : Msg -> Model -> ( Model, Cmd Msg )
update msg model =
    case msg of
        ClickHello ->
            ( { model | loading = True, output = "" }
            , getHello
            )

        GotResponse result ->
            case result of
                Ok response ->
                    ( { model | output = response, loading = False }, Cmd.none )

                Err error ->
                    ( { model | output = httpErrorToString error, loading = False }, Cmd.none )


getHello : Cmd Msg
getHello =
    Http.get
        { url = "/trendwatch"
        , expect = Http.expectString GotResponse
        }


{--
httpErrorToString : Http.Error -> String
httpErrorToString error =
    case error of
        Http.BadUrl url ->
            "Bad URL: " ++ url

        Http.Timeout ->
            "Request timed out"

        Http.NetworkError ->
            "Network error"

        Http.BadStatus statusCode ->
            "Bad status: " ++ String.fromInt statusCode

        Http.BadBody msg ->
            "Bad body: " ++ msg
--}

-- VIEW

view : Model -> Html Msg
view model =
    div []
        [ button [ onClick ClickHello ] [ text "Say Hello" ]
        , if model.loading then
            div [] [ text "Loading..." ]
          else
            div [] [ text model.output ]
        ]
