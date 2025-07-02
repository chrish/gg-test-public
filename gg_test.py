#!/usr/bin/env python3
"""
gitguardian_test_full.py
------------------------
This script intentionally embeds hard‑coded secrets. It demonstrates real connection
logic so that secret‑scanning tools such as GitGuardian have something
to flag. NEVER use these secrets in production!
"""

from __future__ import annotations

import base64
from typing import Any

import mysql.connector
import requests
from cryptography.hazmat.primitives import serialization, hashes
from cryptography.hazmat.primitives.asymmetric import padding
from slack_sdk import WebClient

# ──────────────────────────────────────────────────────────────
# Hard‑coded secrets (throw‑away)
# ──────────────────────────────────────────────────────────────
DB_URI = "mysql://gg_py_user:P!yS3cr3tP@55w0rd@db.example.org:3306/gg_py_db"
DB_HOST = "db.example.org"
DB_USER = "gg_py_user"
DB_PASS = "P!yS3cr3tP@55w0rd"
DB_NAME = "gg_py_db"

API_BASE = "https://api.example.org/rest/v2/list-users"
BEARER_TOKEN = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.dummy.payload.signature"
API_KEY = "ggpy_api_key_0a1b2c3d4e5f6g7h8i9j"

SLACK_BOT_TOKEN = "xoxb-2697155162364-4715167123456-TUVWXYZabcdefghijklmn"

PRIVATE_KEY_PEM = """-----BEGIN RSA PRIVATE KEY-----
MIIEpAIBAAKCAQEA8tXq6SsunU2lJP8gvwx7oVocewbnvQRLm7t6YckClrWqV5D9
mvxjJkvu7UABh1naK2G1bRqE8RM8MkZMKxN/MmYva7w4m0+8SppkQJq1bXcSSk9Z
7ueF7fFvwY5EvKzxgGRQEFmH2VIDUMrKipM64n4EEdk/j+Ai1aKCD/nTw7hwYv/a
aocby/O6fr/J5aQIDAQABAoIBAQCmveqA4eCclUufeTheHg7RFRa4ot/hzZoTsnOd
9nUpmNWTBni/j2/lVd8jMx4lWz8TG2W1u3fDx9VeD72Cc+rJzruPX6WYgnDrGxyA
K3P9U+MGoe7axZTNwM9q/EpSYJmpBYpsPXdLbOkXyjqRZMEe7u6+ly7LJGGEn3/f
nyLQMmeAAoGBAPcWQvQhO5Ki5wk2bBCC3631j/rZWmTZ4YETUQ53t/l83zXhgR+v
H5js1LHds9W67D/kAkAdX5QdvrsJL4efg4HnKGDAlAA1IiOb7zVirtX9QS/MQpUE
UnCnPz6MAt2KbpwOMF7lhhRPllMJZd+vqu/A5LGgxSGoilwZpQ89AoGBAPMAN+9k
KJT3ETQ3gVUPvP3GhtROmXBAoHxmMdd+M9e3iBg6ufv1CQcIE4aYbNKBBCTrNFwt
vZl+cJGaEtbq8EN2nY+sNHAhMxg6PV32kq+GuQ+zrUj25YE5YKk/hDOhC3wrQl8v
F7kjrQUeuHCM1JuDiWwikCtsa9wR4iHMbsjxAoGAKgbq/OjW6JZ7BkDhqF13a+SL
XGifgynINVM5JqQ/ZxjrOKqSd9ZX6Zv7AwEO7oTpQf9gGd6YWlY3NjTf3EjYG7jz
HX8Nhuf19YH0O5F9kdMG9YVAgX6+CFzrTxR5/KCrcxWQI9V3qQvNc01bd55Q5FKS
ALXUTZxHyGaxDMUM8XECgYEAyJt675PCJ4OEVNrtD5O0SgYdkEG6zkG0c8epLzwM
ieszEV7VBMJFTgZ86+4YWmD6iM/hmOr5cfgM0hU/3u/VhOeUxMqk+I6AEPTdBL2D
gE6kcdl3iy4bJ99JplUVR47QPA6u6c3Kwrnc80nQ76tuJesP17j3tZgm1kWbbOG2
Jd0CgYEApr2PuMouZaxbExHcZBTsTXgReGcmmV8Ofl42cDCWpmy69I0iiwv/q4a6
o16xY+FZUp3kpF2TsZ8VDckld4si2IYtWgXDPmnP3FZFd4gJm6u8OMdMwK5MZkNi
n/TwUOmAbrFhAwKGdG9JqjQOdoIb43KmgwcQmEMF6RIPNNNX5zs=
-----END RSA PRIVATE KEY-----"""


# ──────────────────────────────────────────────────────────────
# Database interaction
# ──────────────────────────────────────────────────────────────
def connect_db() -> mysql.connector.connection.MySQLConnection:
    """Connects to the MySQL database using hard‑coded creds."""
    cnx = mysql.connector.connect(
        host=DB_HOST,
        user=DB_USER,
        password=DB_PASS,
        database=DB_NAME,
        port=3306,
    )
    print("[DB] Connected successfully!")
    return cnx


# ──────────────────────────────────────────────────────────────
# API interactions
# ──────────────────────────────────────────────────────────────
def list_users_bearer() -> list[dict[str, Any]]:
    """Lists users using Bearer token auth."""
    headers = {"Authorization": f"Bearer {BEARER_TOKEN}"}
    resp = requests.get(API_BASE, headers=headers, timeout=10)
    resp.raise_for_status()
    print("[API] /list-users (Bearer) status:", resp.status_code)
    return resp.json()


def list_users_api_key() -> list[dict[str, Any]]:
    """Lists users using ?apikey query param."""
    params = {"apikey": API_KEY}
    resp = requests.get(API_BASE, params=params, timeout=10)
    resp.raise_for_status()
    print("[API] /list-users (apikey) status:", resp.status_code)
    return resp.json()


# ──────────────────────────────────────────────────────────────
# Slack bot authentication
# ──────────────────────────────────────────────────────────────
def slack_auth() -> dict[str, Any]:
    """Calls slack.auth_test to confirm token validity."""
    client = WebClient(token=SLACK_BOT_TOKEN)
    auth_resp = client.auth_test()
    print("[Slack] Auth success for workspace:", auth_resp["url"])
    return auth_resp


# ──────────────────────────────────────────────────────────────
# Encryption helper
# ──────────────────────────────────────────────────────────────
def encrypt_with_private_key(message: str) -> str:
    """Encrypts a message using the hard‑coded RSA private key."""
    private_key = serialization.load_pem_private_key(
        PRIVATE_KEY_PEM.encode(),
        password=None,
    )
    ciphertext = private_key.encrypt(
        message.encode(),
        padding.PKCS1v15()
    )
    return base64.b64encode(ciphertext).decode()


if __name__ == "__main__":
    # Minimal smoke test when run directly
    with connect_db() as conn:
        print("[DB] Server version:", conn.get_server_info())

    print("[API] First 2 users via Bearer:", list_users_bearer()[:2])
    print("[API] First 2 users via API key:", list_users_api_key()[:2])

    print("[Slack] Who am I?", slack_auth()["user"])

    print("[Encrypt] sample:", encrypt_with_private_key("Hello GitGuardian!"))
