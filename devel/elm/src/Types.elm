module Types exposing (Model, Msg(..))

import Browser
import Browser.Navigation as Nav
import Http exposing (Error)
import Url exposing (Url)

import Main.Route exposing (Page)
import Page.Confirmation
import Page.Hello
import Page.NotFound
import Page.Positions
import Page.RealizedGains

type alias Model =
    { key : Nav.Key
    , url : Url
    , page : Page
    , output : String
    , loading : Bool
    , helloModel : Page.Hello.Model
    , confirmationModel : Page.Confirmation.Model
    , notFoundModel : Page.NotFound.Model
    , positionsModel : Page.Positions.Model
    , realizedGainsModel : Page.RealizedGains.Model
    }

type Msg
    = NoOp
    | GotResponse (Result Http.Error String)
    | LinkClicked Browser.UrlRequest
    | PageHelloMsg Page.Hello.Msg
    | PageConfirmationMsg Page.Confirmation.Msg
    | PageNotFoundMsg Page.NotFound.Msg
    | PagePositionsMsg Page.Positions.Msg
    | PageRealizedGainsMsg Page.RealizedGains.Msg
    | UrlChanged Url.Url
    