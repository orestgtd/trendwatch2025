# Trendwatch 2025

This project uses Docker Compose for development and deployment tasks. To support flexibility across environments and systems (e.g., Docker Desktop, Lima, or alternative container runtimes), the Docker command is abstracted using the `DOCKER_CMD` environment variable.

## Getting Started

Before running any Docker-related commands, you should configure your environment by creating a `.env` file at the root of the project. You can use the provided `.env.example` as a starting point:

```bash
cp .env.example .env
```

Then, edit the `.env` file to match your system.

## Setting the `DOCKER_CMD` Variable

The `DOCKER_CMD` variable determines how the project invokes Docker Compose. Set this variable in your `.env` file to match the CLI you use:

### Common Options

#### For Docker Desktop:
```env
DOCKER_CMD=docker compose
```

#### For Lima with nerdctl:
```env
DOCKER_CMD=lima nerdctl compose
```

This allows you to use wrapper scripts or commands in the project that rely on `${DOCKER_CMD}` to stay platform-agnostic.

## Example Usage

With `DOCKER_CMD` defined in your `.env` file, you can run project scripts like:

```bash
${DOCKER_CMD} up -d
${DOCKER_CMD} down
```

These commands will resolve to either `docker compose` or `lima nerdctl compose`, depending on your configuration.

---

## .env Management

The actual `.env` file is ignored by Git to prevent machine-specific settings from being committed. The `.env.example` file is tracked and should be updated whenever new environment variables are introduced.

```
.gitignore:
  .env
  .env.*
  !.env.example
```

---

## Contributing

When adding new environment variables, update `.env.example` and document their usage in this README.
