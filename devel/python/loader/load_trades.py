import argparse
import pandas as pd
import requests

API_URL = "http://nginx/api/trades/import"
VALID_TRADE_ACTIONS = ["BUY", "SELL"]

EXPECTED_COLUMNS = [
    "transaction_date",
    "security_number",
    "symbol",
    "trade_number",
    "trade_action",
    "position_effect",
    "description",
    "trade_quantity",
    "unit_type",
    "unit_price",
    "commission",
    "us_tax",
    "net_transaction_amount",
    "expiration_date",
]


def validate_headers(df):

    actual_columns = list(df.columns)

    missing_columns = [
        column
        for column in EXPECTED_COLUMNS
        if column not in df.columns
    ]

    if missing_columns:
        raise ValueError(
            "Invalid CSV headers.\n"
            f"Expected: {EXPECTED_COLUMNS}\n"
            f"Actual:   {actual_columns}\n"
            f"Missing:  {missing_columns}"
        )


def validate_row(row):

    errors = []

    if row["trade_action"] not in VALID_TRADE_ACTIONS:
        errors.append(
            f"Invalid trade action: {row['trade_action']}"
        )

    if row["trade_quantity"] <= 0:
        errors.append(
            f"Invalid quantity: {row['trade_quantity']}"
        )

    return errors


def generate_payloads(df):

    for index, row in df.iterrows():

        errors = validate_row(row)

        if errors:
            df.at[index, "preflight_error"] = "; ".join(errors)
            continue

        yield {
            "row_id": row["Row ID"],
            "transaction_date": row["transaction_date"].strftime("%Y-%m-%d"),
            "security_number": row["security_number"],
            "symbol": row["symbol"],
            "description": row["description"],
            "trade_number": row["trade_number"],
            "trade_action": row["trade_action"],
            "position_effect": row["position_effect"],
            "trade_quantity": row["trade_quantity"],
            "unit_type": row["unit_type"],
            "unit_price": row["unit_price"],
            "commission": row["commission"],
            "us_tax": row["us_tax"],
            "expiration_date": (
                row["expiration_date"].strftime("%Y-%m-%d")
                if pd.notnull(row["expiration_date"])
                else None
            ),
        }


def main():

    parser = argparse.ArgumentParser()
    parser.add_argument("--file", required=True)
    args = parser.parse_args()

    df = pd.read_csv(
        args.file,
        dtype={"trade_number": str, "security_number": str}
    )

    validate_headers(df)

    df["Row ID"] = range(1, len(df) + 1)
    df["preflight_error"] = ""

    df["transaction_date"] = pd.to_datetime(
        df["transaction_date"]
    )

    df["expiration_date"] = pd.to_datetime(
        df["expiration_date"]
    )

    df["commission"] = df["commission"].fillna(0)
    df["us_tax"] = df["us_tax"].fillna(0)

    for payload in generate_payloads(df):

        response = requests.post(
            API_URL,
            json=payload
        )

        print(f"Status Code: {response.status_code}")
        print(response.text)
        print("-" * 40)


if __name__ == "__main__":
    main()
