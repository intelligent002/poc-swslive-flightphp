# API Contract — User API v1

This document defines how the **User API v1** contract is specified,
versioned, and consumed.

The **authoritative contract** is the OpenAPI specification.
This file explains **how to read it**, **what guarantees exist**, and
**what is explicitly out of scope**.

---

## 1. Source of Truth

The canonical API contract is defined in:

```
public/docs/openapi.v1.yaml
```

All clients, tests, and integrations **must conform** to this specification.

No behavior not described in the OpenAPI file should be relied upon.

---

## 2. Human-Readable Views

The OpenAPI contract is rendered automatically in two formats:

### Swagger UI (interactive)

```
/docs/swagger.html
```

https://swslive-flightphp.r7g.org/docs/swagger.html

Use Swagger UI to:

- Explore endpoints
- Inspect schemas
- Execute test requests manually

### Redoc (reference documentation)

```
/docs/redoc.html
```

https://swslive-flightphp.r7g.org/docs/redoc.html

Use Redoc for:

- Clean, read-only API reference
- Schema browsing
- Client onboarding

Both views are generated directly from the OpenAPI spec and are always in sync.

---

## 3. Transport & Encoding Rules

- **Protocol:** HTTP
- **Encoding:** JSON only
- **Content-Type:** `application/json`
- **Character set:** UTF-8

Requests using other encodings are unsupported.

---

## 4. Authentication Model

Authentication is **session-based**.

- A successful `POST /login` establishes a session via cookie
- The cookie is automatically sent by the browser on subsequent requests
- No bearer tokens or API keys are used

Endpoints that require authentication will return:

- `401 Unauthorized` if no valid session exists

---

## 5. Request Semantics

### GET endpoints

- Do **not** accept request bodies
- Identity is derived from the active session

### POST / PUT endpoints

- Accept JSON request bodies as defined in the OpenAPI schemas
- Unknown or extra fields are ignored or rejected depending on validation rules

---

## 6. Response Envelope

All API responses follow a consistent envelope pattern.

### Success

```json
{
  "ok": true
}
```

or

```json
{
  "ok": true,
  "data": {
    ...
  }
}
```

### Error

```json
{
  "ok": false,
  "errors": {
    "field": "error message"
  }
}
```

- Error keys correspond to logical input fields or error categories
- Multiple validation errors may be returned in a single response

---

## 7. Validation Rules

Validation rules are enforced server-side and reflected in OpenAPI schemas.

Examples:

- Email must be valid and unique
- Date of birth must be a valid past date
- User must be at least 18 years old
- Passwords must meet minimum length and confirmation rules

Exact validation messages are **not part of the contract** and may change.

Clients must rely on error keys, not message strings.

---

## 8. Status Code Guarantees

The following status codes are used intentionally:

| Code | Meaning                  |
|------|--------------------------|
| 200  | Success                  |
| 401  | Unauthorized             |
| 409  | Duplicate email conflict |
| 422  | Validation errors        |
| 503  | Infra level issues       |

No other status codes are guaranteed.

---

## 9. Versioning Policy

- This document applies to **API v1**
- Backward-compatible changes may be introduced without version bump
- Breaking changes require a new major version (`v2`)

The OpenAPI file name reflects the major version:

```
openapi.v1.yaml
```

---

## 10. What Is Explicitly Out of Scope

The following are **not** part of the contract:

- Database structure
- Internal identifiers (user UUIDs)
- Session storage mechanics
- Exact wording of validation messages
- UI behavior

---

## 11. Contract Changes

Any change that affects:

- Request/response shape
- Required fields
- Status codes
- Authentication behavior

**must** be reflected in:

1. `openapi.v1.yaml`
2. Swagger & Redoc views

This file (`CONTRACT.md`) only changes if contract governance changes.

---

## 12. Summary

- OpenAPI is the contract
- Swagger & Redoc are the documentation
- This file defines expectations and guarantees
- If it is not in OpenAPI, it is not supported

