module Main.Route exposing (Page(..), urlToPage)

import Url exposing (Url)
import Url.Parser as Parser exposing ((</>), Parser, s, top)


-- Define your pages

type Page
    = BuyWritePage
    | ConfirmationPage
    | HelloPage
    | NotFound
    | PositionsPage
    | RealizedGainsPage


-- Parser for routes

routeParser : Parser (Page -> a) a
routeParser =
    Parser.oneOf
        [ top |> Parser.map ConfirmationPage
        , s "buywrite" |> Parser.map BuyWritePage
        , s "hello" |> Parser.map HelloPage
        , s "positions" |> Parser.map PositionsPage
        , s "realized_gains" |> Parser.map RealizedGainsPage
        ]


-- Convert Url to Page using parser

urlToPage : Url -> Page
urlToPage url =
    Parser.parse routeParser url
        |> Maybe.withDefault NotFound
