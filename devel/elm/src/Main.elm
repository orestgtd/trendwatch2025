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
import Page.NotFound
import Page.Positions
import Page.RealizedGains

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
      , confirmationModel = Page.Confirmation.init
      , helloModel = Page.Hello.init
      , notFoundModel = Page.NotFound.init
      , positionsModel = Page.Positions.init
      , realizedGainsModel = Page.RealizedGains.init
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

        LinkClicked urlRequest ->
            case urlRequest of
                Browser.Internal url ->
                    ( model, Nav.pushUrl model.key (Url.toString url) )

                Browser.External href ->
                    ( model, Nav.load href )

        NoOp ->
            ( model, Cmd.none )

        PageConfirmationMsg subMsg ->
            let
                ( updatedConfirmationModel, cmd ) =
                    Page.Confirmation.update subMsg model.confirmationModel
            in
            ( { model | confirmationModel = updatedConfirmationModel }
            , Cmd.map PageConfirmationMsg cmd
            )

        PageHelloMsg subMsg ->
            let
                (updatedHelloModel, cmd) =
                    Page.Hello.update subMsg model.helloModel
            in
            ( { model | helloModel = updatedHelloModel }, Cmd.map PageHelloMsg cmd )

        PageNotFoundMsg _ ->
            ( model, Cmd.none )

        PagePositionsMsg subMsg ->
            let
                ( updatedPositionsModel, cmd ) =
                    Page.Positions.update subMsg model.positionsModel
            in
            ( { model | positionsModel = updatedPositionsModel }
            , Cmd.map PagePositionsMsg cmd
            )

        PageRealizedGainsMsg subMsg ->
            let
                ( updatedRealizedGainsModel, cmd ) =
                    Page.RealizedGains.update subMsg model.realizedGainsModel
            in
            ( { model | realizedGainsModel = updatedRealizedGainsModel }
            , Cmd.map PageRealizedGainsMsg cmd
            )

        UrlChanged newUrl ->
            let
                newPage = urlToPage newUrl
            in
            ( { model | url = newUrl, page = newPage }, Cmd.none )


-- VIEW

view : Model -> Browser.Document Msg
view model =
    let
        pageContent =
            case model.page of
                BuyWritePage ->
                    Html.text "BuyWrite page not implemented yet"

                ConfirmationPage ->
                    Page.Confirmation.view model.confirmationModel
                        |> Html.map PageConfirmationMsg

                HelloPage ->
                    Page.Hello.view model.helloModel
                        |> Html.map PageHelloMsg

                PositionsPage ->
                    Page.Positions.view model.positionsModel
                        |> Html.map PagePositionsMsg

                RealizedGainsPage ->
                    Page.RealizedGains.view model.realizedGainsModel
                        |> Html.map PageRealizedGainsMsg

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
        , link "/hello" "Hello"
        , link "/positions" "Positions"
        , link "/realized_gains" "Realized Gains"
        ]

-- HTTP

getHello : Cmd Msg
getHello =
    Http.get
        { url = "/trendwatch"
        , expect = Http.expectString GotResponse
        }
