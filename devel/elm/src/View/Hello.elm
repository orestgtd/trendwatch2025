module View.Hello exposing (view)

import Html exposing (Html, h1, text)

view : Html msg
view =
    h1 [] [ text "Hello Page" ]
