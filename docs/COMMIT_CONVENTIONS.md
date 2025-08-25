# Commit Message Conventions

This document defines the conventions for commit message prefixes in the **Trendwatch** project. Consistent commit messages make it easier to understand the history of changes and to generate changelogs.

---

## Standard Prefixes

We use the following standard prefixes:

- **chore:** Routine tasks that do not affect application logic (e.g., build process, dependencies, configs).
- **fix:** A bug fix that resolves incorrect or broken behavior.
- **feat:** A new feature or enhancement.
- **docs:** Documentation-only changes.
- **style:** Code style updates that do not affect behavior (e.g., formatting, whitespace).

---

## Technology-Specific Prefixes

When a change is specific to one of our main technology stacks, we prefix the message with the technology name:

- **laravel:** A change that affects the Laravel (backend) codebase.
- **elm:** A change that affects the Elm (frontend) codebase.

---

## Combining Prefixes

The desired convention is to **combine a technology-specific prefix with a standard prefix**, and optionally a scope for clarity:

```
<tech>: <standard>(<scope>): short description of the change
```

- `<tech>` = `laravel` or `elm` (lowercase)
- `<standard>` = one of the standard prefixes (e.g., `feat`, `fix`, `chore`)
- `<scope>` = optional, indicates the module, layer, or area affected
- Description in imperative mood

**Examples:**
- `laravel: feat(shared): add Result class for unified success/failure handling`
- `elm: feat(page): integrate new Page modules into Main.elm and Types.elm`
- `laravel: fix: correct portfolio calculation query`
- `elm: style: update holdings view layout`

---

## Examples

- `chore: update dependencies`
- `fix: resolve bug in trade matching algorithm`
- `docs: add API usage section`
- `style: reformat Elm modules`
- `laravel: feat(api): add new endpoint for trade confirmations`
- `elm: chore: update package.json for new compiler`
