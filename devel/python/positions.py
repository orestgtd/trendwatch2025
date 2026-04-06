import requests
from tabulate import tabulate

# api_url = os.getenv('HELLO_API_URL', 'http://localhost:8080/api/hello')

api_url = 'http://nginx/api/positions'

response = requests.get(api_url)

data = response.json()["data"]

rows = []

for position in data:
    rows.append([
        position["security_number"],
        position["symbol"],
        position["position_type"],
        position["quantity"],
        position["cost"],
        position["proceeds"],
        position["expiration_date"],
    ])

print(
    tabulate(
        rows,
        headers=[
            "Security",
            "Symbol",
            "Position Type",
            "Qty",
            "Cost",
            "Proceeds",
            "Expiration Date",
        ],
        tablefmt="grid",
    )
)

print(f"\n{len(rows)} positions listed.")
