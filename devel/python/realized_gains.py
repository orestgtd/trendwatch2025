import requests
from tabulate import tabulate

api_url = 'http://nginx/api/realized_gains'

response = requests.get(api_url)

print(f"Status: {response.status_code}")

response.raise_for_status()

realized_gains = response.json()['data']

if not realized_gains:
    print("No realized gains found.")
else:

    rows = []

    for gain in realized_gains:
        rows.append([
            gain["security_number"],
            gain["realization_date"],
            gain["position_type"],
            gain["realization_source"],
            gain["base_quantity"],
            gain["trade_quantity"],
            gain["unit_type"],
            gain["cost"],
            gain["proceeds"],
        ])

    print(
        tabulate(
            rows,
            headers=[
                "Security",
                "Realization Date",
                "Position",
                "Source",
                "Base Qty",
                "Trade Qty",
                "Unit Type",
                "Cost",
                "Proceeds",
            ],
            tablefmt="grid",
        )
    )

    print(f"\n{len(realized_gains)} realized gains listed.")
