# FlightPHP Interview Demo

A small, self-contained **SPA + API** demo built with **FlightPHP**, **PDO**, and
**session-based authentication**.

Designed specifically as a **technical interview sample** to demonstrate
API design discipline, backend structure, and testability.

---

## Scope & Intent

This project is intentionally compact and focuses on **correctness, clarity,
and architectural hygiene**, rather than feature completeness.

### Included

- REST API with proper HTTP semantics
- JSON-only request/response contract
- Session-based authentication (cookie-backed)
- Clear layering:
    - Controllers
    - Models
    - Validation
    - DAL (Data Access Layer)
- Simple SPA frontend (jQuery + Bootstrap)
- Deterministic unit tests for core model logic
- Mocked DAL via a DB abstraction (real PDO swapped for a fake in tests)
- OpenAPI v3 specification with Swagger UI and Redoc

### Explicitly Excluded

These omissions are deliberate to keep the demo focused and reviewable:

- Docker / containerization
- Environment configuration layers
- CSRF protection
- Role-based authorization
- Dependency injection frameworks
- End-to-end or browser tests

---

## Architecture Overview

### Backend

- **Framework:** FlightPHP
- **Auth:** Cookie-based PHP sessions
- **Persistence:** PDO (MySQL)
- **Schema:** `db/structure.sql`

Database access is isolated behind a small `DBConnection` interface:

- In production, it wraps a real PDO instance
- In unit tests, it is replaced with a fake implementation

This allows full model-level testing without a database.

### Frontend

- jQuery + Bootstrap
- SPA-style navigation (hash-based)
- JSON-only API communication
- No server-side rendering

---

## API Contract & Documentation

The API contract is formally defined using **OpenAPI v3**.

### Contract Governance

The authoritative contract is described in:

```
CONTRACT.md
```

This file explains:
- What is considered part of the API contract
- What guarantees are provided
- What is explicitly out of scope

All consumers must rely on the OpenAPI specification as the source of truth.

---

## Configuration

### Backend

Environment variables

Prior to running the application, the following environment variables must be exported, as they are required to establish the database connection in `App/bootstrap.php`:

```bash
export DB_HOST=mysql-master.intel.r7g.org
export DB_NAME=poc-flightphp
export DB_USER=app_user
export DB_PASS=strong-secret
```
### Tests

Unit tests use a dedicated test bootstrap that injects a fake DB connection, 
which enables deterministic tests with no external dependencies.

---

## Deployment Notes

Only the `public/` directory should be exposed as the web server document root.

All application code, configuration, and bootstrap logic live **outside** the
public web scope and must not be directly accessible.

After publishing the `public/` directory, install Composer dependencies from
the project root:

```bash
composer install
```

To run the unit tests:

```bash
./vendor/bin/phpunit
```

---

## Summary

This repository demonstrates:

- Clean separation of concerns
- A well-defined, versioned API contract
- JSON-only API discipline
- Testable backend architecture
- Pragmatic scope control suitable for interview review

It is intentionally minimal, explicit, and easy to reason about.

