# Python Container Setup

The Python container provides a Python 3.12 development environment for scripts, data preparation, and integration with the Laravel API.

The container is managed through the project's root Docker Compose configuration, alongside the Laravel, Nginx, and Elm services.

## Dependencies

Python dependencies are managed through requirements files in `docker/python-3.12/`.

- `requirements.txt` — Runtime and application dependencies.
- `requirements-dev.txt` — Development dependencies, including pytest. This file includes `requirements.txt`.

The current environment includes:

- **JupyterLab** — Interactive Python development.
- **pandas** — Data manipulation.
- **requests** — HTTP requests to the Laravel API.
- **tabulate** — Tabular output.
- **pytest** — Automated testing.

When dependencies change, rebuild the Python image to install the updated packages.

## Building the Container

From the project root, build the Python image:

```bash
task build:python
```

This builds the `notebook` service without rebuilding the other service images.

To start the development environment, use the project's normal startup command:

```bash
task up
```

## Shell Access

Open an interactive shell in the Python container:

```bash
task pysh
```

The Python container's working directory is `/workspace/python`.

The project root is mounted at `/workspace`, making the project's Python source and tests available inside the container.

## Running Scripts

From the Python directory inside the container, run a script using Python 3:

```bash
python3 hello.py
```

The `python/Makefile` also provides a shortcut:

```bash
make hello
```

## Running Tests

Tests are written using pytest and are located in `python/tests/`.

Run the test suite from the Python directory inside the container:

```bash
make test
```

Alternatively, invoke pytest directly:

```bash
python3 -m pytest -v
```

The `-v` option enables verbose output, showing individual test results.

### Integration Tests

Integration tests exercise the running application through its HTTP API.

The initial integration test, `tests/test_hello_integration.py`, calls the Laravel Hello endpoint at `http://nginx/api/hello` and verifies the HTTP status and JSON response.

For this test to pass, the Laravel and Nginx services must be running, and the Python container must be connected to the same Docker network.

## Docker Networking

Within the Docker network, services can communicate using their Compose service names.

For example, the Python container accesses the Laravel API through Nginx at:

```text
http://nginx/api/hello
```

This URL is intended for communication between containers. When accessing the API directly from the host, use the host-published port instead, such as `http://localhost:8080/api/hello`.

## Related Documentation

- [Environment Setup](env-setup.md)
- [Elm Container Setup](elm-container.md)
