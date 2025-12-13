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

import Page.Confirmation
import Page.Hello
import Page.Positions
import Page.NotFound

import Util.Http exposing (httpErrorToString)

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
      , helloModel = Page.Hello.init
      , confirmationModel = Page.Confirmation.init
      , positionsModel = Page.Positions.init
      , notFoundModel = Page.NotFound.init
      }
    , Cmd.none
    )

-- UPDATE

update : Msg -> Model -> ( Model, Cmd Msg )
update msg model =
    case msg of

        GotResponse result ->
            case result of
                Ok response ->
                    ( { model | output = response, loading = False }, Cmd.none )

                Err error ->
                    ( { model | output = httpErrorToString error, loading = False }, Cmd.none )

        PageHelloMsg subMsg ->
            let
                (updatedHelloModel, cmd) =
                    Page.Hello.update subMsg model.helloModel
            in
            ( { model | helloModel = updatedHelloModel }, Cmd.map PageHelloMsg cmd )

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

        PageConfirmationMsg subMsg ->
            let
                ( updatedConfirmationModel, cmd ) =
                    Page.Confirmation.update subMsg model.confirmationModel
            in
            ( { model | confirmationModel = updatedConfirmationModel }
            , Cmd.map PageConfirmationMsg cmd
            )

        PagePositionsMsg subMsg ->
            let
                ( updatedPositionsModel, cmd ) =
                    Page.Positions.update subMsg model.positionsModel
            in
            ( { model | positionsModel = updatedPositionsModel }
            , Cmd.map PagePositionsMsg cmd
            )

        PageNotFoundMsg _ ->
            ( model, Cmd.none )

        NoOp ->
            ( model, Cmd.none )


-- VIEW

view : Model -> Browser.Document Msg
view model =
    let
        pageContent =
            case model.page of
                ConfirmationPage ->
                    Page.Confirmation.view model.confirmationModel
                        |> Html.map PageConfirmationMsg


                PositionsPage ->
                    Page.Positions.view model.positionsModel
                        |> Html.map PagePositionsMsg

                HelloPage ->
                    Page.Hello.view model.helloModel
                        |> Html.map PageHelloMsg

                BuyWritePage ->
                    Html.text "BuyWrite page not implemented yet"

                NotFound ->
                    Page.NotFound.view model.notFoundModel
                        |> Html.map PageNotFoundMsg
    in
    { title = "My App"
    , body =
        [ viewLinks
        , Html.div [] [ pageContent ]
        ]
    }

link : String -> String -> Html Msg
link url label =
    Html.a
        [ Html.Attributes.href url
        ]
        [ text label ]

{-
safeUrl : String -> Url.Url
safeUrl str =
    Url.Url Url.Http "" Nothing str Nothing Nothing
-}

viewLinks : Html Msg
viewLinks =
    Html.div []
        [ link "/" "Confirmation"
        , link "/positions" "Positions"
        , link "/hello" "Hello"
        ]

-- HTTP

getHello : Cmd Msg
getHello =
    Http.get
        { url = "/trendwatch"
        , expect = Http.expectString GotResponse
        }
