module Types exposing (Model, Msg(..))

import Browser
import Browser.Navigation as Nav
import Http exposing (Error)
import Url exposing (Url)

import Main.Route exposing (Page)
import Page.Confirmation
import Page.Hello
import Page.Positions
import Page.NotFound

type alias Model =
    { key : Nav.Key
    , url : Url
    , page : Page
    , output : String
    , loading : Bool
    , helloModel : Page.Hello.Model
    , confirmationModel : Page.Confirmation.Model
    , positionsModel : Page.Positions.Model
    , notFoundModel : Page.NotFound.Model
    }

type Msg
    = NoOp
    | GotResponse (Result Http.Error String)
    | PageHelloMsg Page.Hello.Msg
    | PageConfirmationMsg Page.Confirmation.Msg
    | PagePositionsMsg Page.Positions.Msg
    | PageNotFoundMsg Page.NotFound.Msg
    | UrlChanged Url.Url
    | LinkClicked Browser.UrlRequest
    