module View.NotFound exposing (view)

import Html exposing (Html, h1, text)

view : Html msg
view =
    h1 [] [ text "404 - Page Not Found" ]
