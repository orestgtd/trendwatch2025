module Util.Money exposing (Money, fromAmountAndCurrency, format)

-- MODEL

type alias Money =
    { amount : Float
    , currency : String
    }

-- CONSTRUCTOR

fromAmountAndCurrency : Float -> String -> Money
fromAmountAndCurrency amount currency =
    { amount = amount, currency = currency }

-- VIEW / FORMAT

format : Money -> String
format money =
    "$" ++ String.fromFloat money.amount ++ " " ++ money.currency
