import requests

api_url = 'http://nginx/api/hello'

def test_hello_api_returns_expected_message() -> None:
    response = requests.get(
        api_url,
        timeout=5,
    )

    assert response.status_code == 200
    assert response.json() == {
        'message': 'Hello from Laravel!',
    }
