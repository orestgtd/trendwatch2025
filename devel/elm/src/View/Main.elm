module View.Main exposing (viewBody)

import Html exposing (Html, button, div, text)
import Html.Attributes exposing (disabled, style)
import Html.Events exposing (onClick)
import Types exposing (Model, Msg(..))

viewBody : Model -> Html Msg
viewBody model =
    div []
        [ button [ onClick ClickHello, disabled model.loading ] [ text "Hello" ]
        , div [ style "margin-top" "1em" ]
            [ text (if model.loading then "Loading..." else model.output) ]
        ]
