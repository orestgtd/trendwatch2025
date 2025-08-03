module Main exposing (main)

import Browser
import Html exposing (Html, button, div, text)
import Html.Attributes exposing (..)
import Html.Events exposing (onClick)
import Http

import Types exposing (Model, Msg(..))
import View.Main exposing (viewBody)

-- MAIN


main : Program () Model Msg
main =
    Browser.element
        { init = \_ -> ( initialModel, Cmd.none )
        , view = view
        , update = update
        , subscriptions = \_ -> Sub.none
        }


-- MODEL

initialModel : Model
initialModel =
    { output = ""
    , loading = False
    }


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


-- VIEW

view : Model -> Html Msg
view model =
    viewBody model


-- HTTP

getHello : Cmd Msg
getHello =
    Http.get
        { url = "/trendwatch"
        , expect = Http.expectString GotResponse
        }

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
