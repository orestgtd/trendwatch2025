module Main exposing (main)

import Browser
import Browser.Navigation as Nav
import Html exposing (Html, button, div, text)
import Html.Attributes exposing (..)
import Html.Events exposing (onClick)
import Http
import Browser.Navigation as Nav
import Url exposing (Url)

import Main.Route exposing (Page(..), urlToPage)
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
    let
        page = urlToPage url
    in
    ( { key = key
      , url = url
      , page = page
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
            let
                newPage = urlToPage newUrl
            in
            ( { model | url = newUrl, page = newPage }, Cmd.none )

        LinkClicked urlRequest ->
            case urlRequest of
                Browser.Internal url ->
                    ( model, Nav.pushUrl model.key (Url.toString url) )

                Browser.External href ->
                    ( model, Nav.load href )

-- VIEW

{--
view : Model -> Browser.Document Msg
view model =
    { title = "Trendwatch"
    , body = [ viewBody model ]
    }
--}

{--
view : Model -> Browser.Document Msg
view model =
    case model.page of
        ConfirmationPage ->
            { title = "Confirmation"
            , body = [ Html.h1 [] [ text "Confirmation Page" ] ]
            }

        PositionsPage ->
            { title = "Positions"
            , body = [ Html.h1 [] [ text "Positions Page" ] ]
            }

        BuyWritePage ->
            { title = "BuyWrite"
            , body = [ Html.h1 [] [ text "BuyWrite Page" ] ]
            }

        HelloPage ->
            { title = "Hello"
            , body = [ Html.h1 [] [ text "Hello Page" ] ]
            }

        NotFound ->
            { title = "Page Not Found"
            , body = [ Html.h1 [] [ text "404 - Page Not Found" ] ]
            }
--}

view : Model -> Browser.Document Msg
view model =
    let
        pageContent =
            case model.page of
                ConfirmationPage ->
                    Html.h1 [] [ Html.text "Confirmation Page" ]

                PositionsPage ->
                    Html.h1 [] [ Html.text "Positions Page" ]

                BuyWritePage ->
                    Html.h1 [] [ Html.text "BuyWrite Page" ]

                HelloPage ->
                    Html.h1 [] [ Html.text "Hello Page" ]

                NotFound ->
                    Html.h1 [] [ Html.text "404 - Page Not Found" ]
    in
    { title = "My App"
    , body =
        [ viewLinks  -- <-- Insert navigation links here
        , Html.div [] [ pageContent ]  -- Your page-specific content below the links
        ]
    }

link : String -> String -> Html Msg
link url label =
    Html.a
        [ Html.Attributes.href "#"
        , Html.Events.onClick (LinkClicked (Browser.Internal (safeUrl url)))
        ]
        [ text label ]

safeUrl : String -> Url.Url
safeUrl str =
    Url.Url Url.Http "" Nothing str Nothing Nothing

viewLinks : Html Msg
viewLinks =
    Html.div []
        [ link "/" "Confirmation"
        , link "/positions" "Positions"
        , link "/buywrite" "BuyWrite"
        ]


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
