module Main exposing (main)

import Browser
import Html exposing (Html, button, div, text)
import Html.Attributes exposing (..)
import Html.Events exposing (onClick)
import Http
import Browser.Navigation as Nav
import Url exposing (Url)

import Types exposing (Model, Msg(..))
import View.Main exposing (viewBody)

-- MAIN


main : Program () Model Msg
main =
    Browser.application
        { init = init
        , view = view
        , update = update
        , subscriptions = \_ -> Sub.none
        , onUrlChange = UrlChanged
        , onUrlRequest = LinkClicked
        }

-- MODEL

init : () -> Url -> Nav.Key -> ( Model, Cmd Msg )
init _ url key =
    ( { key = key
      , url = url
      , output = ""
      , loading = False
      }
    , Cmd.none
    )

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

        UrlChanged newUrl ->
            ( { model | url = newUrl }, Cmd.none )

        LinkClicked urlRequest ->
            case urlRequest of
                Browser.Internal url ->
                    ( model, Nav.pushUrl model.key (Url.toString url) )

                Browser.External href ->
                    ( model, Nav.load href )

-- VIEW

view : Model -> Browser.Document Msg
view model =
    { title = "Trendwatch"
    , body = [ viewBody model ]
    }

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
