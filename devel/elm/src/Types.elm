module Types exposing (Model, Msg(..))

import Browser
import Browser.Navigation as Nav
import Http exposing (Error)
import Url exposing (Url)

type alias Model =
    { key : Nav.Key
    , url : Url
    , output : String
    , loading : Bool
    }

type Msg
    = ClickHello
    | GotResponse (Result Http.Error String)
    | UrlChanged Url
    | LinkClicked Browser.UrlRequest
