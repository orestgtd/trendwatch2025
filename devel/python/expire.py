import argparse
import requests


parser = argparse.ArgumentParser()
parser.add_argument("--asof", required=True)

args = parser.parse_args()

asof = args.asof

api_url = 'http://nginx/api/positions/expire'

response = requests.post(
    api_url,
    params={'asof': asof},
)

print(response.status_code)
print(response.json())
