module Types exposing (Model, Msg(..))

import Http exposing (Error)

type alias Model =
    { output : String
    , loading : Bool
    }

type Msg
    = ClickHello
    | GotResponse (Result Error String)
