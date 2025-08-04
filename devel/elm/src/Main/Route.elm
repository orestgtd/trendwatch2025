module Main.Route exposing (Page(..), urlToPage)

import Url exposing (Url)
import Url.Parser as Parser exposing ((</>), Parser, s, top)


-- Define your pages

type Page
    = ConfirmationPage
    | PositionsPage
    | BuyWritePage
    | HelloPage
    | NotFound


-- Parser for routes

routeParser : Parser (Page -> a) a
routeParser =
    Parser.oneOf
        [ top |> Parser.map ConfirmationPage
        , s "positions" |> Parser.map PositionsPage
        , s "buywrite" |> Parser.map BuyWritePage
        , s "hello" |> Parser.map HelloPage
        ]


-- Convert Url to Page using parser

urlToPage : Url -> Page
urlToPage url =
    Parser.parse routeParser url
        |> Maybe.withDefault NotFound
