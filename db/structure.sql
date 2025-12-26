CREATE TABLE users
(
    # UUID v7 for the index
    id            BINARY(16)     NOT NULL PRIMARY KEY,
    email         VARCHAR(255)   NOT NULL,
    name          VARCHAR(100)   NOT NULL,
    date_of_birth DATE           NOT NULL,
    password_hash VARBINARY(255) NOT NULL,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    # Constraint for unique emails (registration depends on it)
    UNIQUE KEY ux_users_email (email),

    # Covering index for logins
    KEY ix_users_auth (email, password_hash, id)
);

# view to see the UUIDv7 in formatted way
CREATE OR REPLACE VIEW users_with_uuid AS
SELECT UPPER(BIN_TO_UUID(id)) AS id,
       email,
       name,
       date_of_birth,
       password_hash,
       created_at,
       updated_at
FROM users;