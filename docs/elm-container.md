# Elm Development Container

This document describes the setup and usage of the Elm container in the Trendwatch development environment. It is intended for contributors working on the Elm layer of the application.

---

## Overview

The Elm container is part of the multi-container setup used during local development. It provides a lightweight Alpine-based environment with Elm and related tools pre-installed.

Key features:
- Runs Elm 0.19.1 in a dedicated container
- Uses Alpine Linux with Node, npm, and Bash
- Includes Tailwind CSS for rapid prototyping
- Starts in `/app/elm` by default
- Supports interactive shell access via `task elmsh`

---

## Getting Started

To start the full environment (PHP, Nginx, Elm, etc.):

```
task up
```

To open a Bash shell inside the Elm container:

```
task elmsh
```

> Note: Exiting the shell may print a harmless error like `exit status 2` due to how Task handles exit codes. This is expected and has no effect on container stability.

---

## Container Configuration

### Dockerfile Highlights

- Based on `alpine:3.21`
- Installs `node`, `npm`, `bash`, and `build-base`
- Installs `elm@0.19.1` and `elm-test` globally
- Adds Tailwind CSS via CDN to `/app/static/css`
- Customizes `.bashrc` to show version info on login

### docker-compose.yml Highlights

The `elm` service is configured with:

```yaml
working_dir: /app/elm
tty: true
stdin_open: true
```

This ensures:
- All shells open in the correct working directory
- The container stays alive for interactive use

---

## Taskfile Integration

The `elmsh` task in `Taskfile.yml` provides a convenient way to open a shell:

```yaml
elmsh:
  desc: "Open a shell into the Elm container"
  cmds:
    - '{{.docker_cmd}} exec -it elm /bin/bash || true'
```

The `|| true` prevents Task from reporting a false failure when the shell exits with a non-zero code (which is normal in some Bash sessions).

---

## Common Pitfalls

### “Service not running” Error

If you see:

```
service "elm" is not running
```

It usually means the container started and exited immediately. This is prevented by:

- Setting `tty: true` and `stdin_open: true` in `docker-compose.yml`
- Avoiding a `CMD ["/bin/bash"]` line in the Dockerfile (not needed when using `exec`)

### Wrong Working Directory

If the shell drops you into `/app` instead of `/app/elm`, check:

- `working_dir: /app/elm` is set correctly in `docker-compose.yml`
- You’ve restarted the containers after making config changes (`task down && task up`)

---

## Commit Guidelines for Related Changes

When making changes across `Taskfile.yml`, `docker-compose.yml`, and Dockerfiles that support Elm development, it's fine to group them in a single commit.

Example commit message:

```
dev: improve Elm shell usability and container setup

- docker: set working_dir for Elm container to /app/elm for better context isolation
- docker: enable tty and stdin_open to keep container alive for interactive shell use
- docker: remove CMD ["/bin/bash"] from Dockerfile since exec explicitly calls bash
- task: append `|| true` to elmsh task to prevent exit status errors after shell closes
```

Use a single high-level prefix (e.g. `dev:`) in the subject line, and inline prefixes in the body for clarity.

---

## Related Topics

- [Task CLI](https://taskfile.dev)
- [Elm Language](https://elm-lang.org/)
- [Alpine Linux](https://alpinelinux.org/)
- [Docker Compose](https://docs.docker.com/compose/)
