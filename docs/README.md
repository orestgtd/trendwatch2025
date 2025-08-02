# Trendwatch 2025

This project uses Docker Compose for development and deployment. It is designed to work across platforms (e.g., Docker Desktop, Lima) through environment abstraction and container layering.

## Developer Guide

- 🛠 [Environment Setup](docs/env-setup.md): `.env` file, `DOCKER_CMD`, and usage
- 📦 [Elm Container Setup](docs/elm-container.md): Shell access, working directory, and volume notes

## Quick Start

1. Copy `.env.example` to `.env` and update values for your system.
2. Run:
   ```
   task up
   ```
3. To shell into the Elm container:
   ```
   task elmsh
   ```

## Project Layout

```
devel/
├── docker/
│   ├── php-8.2-fpm/
│   └── elm/
├── docker-compose.yml
├── .env.example
├── Taskfile.yml
└── docs/
    ├── env-setup.md
    └── elm-container.md
```

## Contributions

When adding new environment variables or container layers:
- Update `.env.example`
- Document changes in the appropriate file under `docs/`
