# OAuth2 Stateless API Authentication with Laravel Passport

This API utilizes Laravel Passport for OAuth2 authentication, designed with stateless principles but incorporating refresh tokens for enhanced user experience. This document outlines how to interact with the API using access tokens and how to renew them.

## Stateless API Concept

A stateless API means that the server does not store any session information about the client between requests. Each request from the client must contain all the necessary information for the server to process it. In the context of OAuth2 with refresh tokens, this means:

*   **Short-lived Access Tokens:** Access tokens are designed to be short-lived (e.g., 1 day) for security.
*   **Long-lived Refresh Tokens:** Refresh tokens allow clients to obtain new access tokens without requiring the user to re-enter their credentials. While the server must store and validate refresh tokens, this mechanism supports a stateless API by enabling new access token issuance without maintaining a continuous user session.

## Obtaining Access Tokens

You can obtain access tokens primarily using the following OAuth2 grants:

### 1. Password Grant (for first-party clients)

This grant is suitable for your own first-party applications (e.g., a mobile app or a frontend web application where users directly log in).

**Request:**

```http
POST /oauth/token HTTP/1.1
Host: your-api-domain.com
Content-Type: application/json

{
    "grant_type": "password",
    "client_id": "YOUR_CLIENT_ID",
    "client_secret": "YOUR_CLIENT_SECRET",
    "username": "user@example.com",
    "password": "your-password",
    "scope": ""
}
```

*   `client_id`: The ID of your password grant client (generated during `php artisan passport:install`).
*   `client_secret`: The secret of your password grant client.
*   `username`: The user's email or username.
*   `password`: The user's password.
*   `scope`: (Optional) The desired scopes for the token. Leave empty for all available scopes.

**Response (Success):**

```json
{
    "token_type": "Bearer",
    "expires_in": 86400, // 24 hours
    "access_token": "eyJ0eXAiOiJKV1Qi...",
    "refresh_token": "def502000a6e0c7a52aa7a48d085dfb90c..."
}
```

### 2. Client Credentials Grant (for machine-to-machine communication)

This grant is suitable for machine-to-machine authentication where a client needs to access resources without a user's direct involvement.

**Request:**

```http
POST /oauth/token HTTP/1.1
Host: your-api-domain.com
Content-Type: application/json

{
    "grant_type": "client_credentials",
    "client_id": "YOUR_CLIENT_ID",
    "client_secret": "YOUR_CLIENT_SECRET",
    "scope": ""
}
```

*   `client_id`: The ID of your client credentials client.
*   `client_secret`: The secret of your client credentials client.
*   `scope`: (Optional) The desired scopes for the token.

**Response (Success):**

```json
{
    "token_type": "Bearer",
    "expires_in": 86400, // 24 hours
    "access_token": "eyJ0eXAiOiJKV1Qi..."
}
```

## Renewing Access Tokens using Refresh Token

When your access token expires, you can use the `refresh_token` obtained from the password grant to acquire a new access token without re-authenticating the user.

**Request:**

```http
POST /oauth/token HTTP/1.1
Host: your-api-domain.com
Content-Type: application/json

{
    "grant_type": "refresh_token",
    "refresh_token": "YOUR_REFRESH_TOKEN",
    "client_id": "YOUR_CLIENT_ID",
    "client_secret": "YOUR_CLIENT_SECRET",
    "scope": ""
}
```

*   `refresh_token`: The refresh token you received from a previous password grant request.
*   `client_id`: The ID of your password grant client.
*   `client_secret`: The secret of your password grant client.
*   `scope`: (Optional) The desired scopes for the new access token.

**Response (Success):**

```json
{
    "token_type": "Bearer",
    "expires_in": 86400, // 24 hours
    "access_token": "eyJ0eXAiOiJKV1Qi...",
    "refresh_token": "new_def502000a6e0c7a52aa7a48d085dfb90c..." // A new refresh token is usually issued
}
```

## Using Access Tokens for API Requests

Once you have an `access_token`, include it in the `Authorization` header of your API requests as a `Bearer` token.

**Request Example:**

```http
GET /api/v1/user HTTP/1.1
Host: your-api-domain.com
Authorization: Bearer eyJ0eXAiOiJKV1Qi...
Accept: application/json
```

## Token Expiration

Access tokens are configured to expire after 24 hours. When an access token expires, you will receive an `Unauthenticated` error (typically HTTP 401). At this point, your client application should use the valid `refresh_token` to obtain a new `access_token` and `refresh_token`. If the `refresh_token` also expires or is revoked, the user will need to re-authenticate with their credentials.

This design ensures the API remains stateless in terms of active user sessions, while still providing a convenient way to renew access tokens.
