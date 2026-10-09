# import os
import requests

# api_url = os.getenv('HELLO_API_URL', 'http://localhost:8080/api/hello')

api_url = 'http://nginx/api/hello'

response = requests.get(api_url)

print(response.json()['message'])
