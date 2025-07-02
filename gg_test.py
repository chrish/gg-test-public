#!/usr/bin/env python3
"""
gitguardian_test.py
-------------------
Intentionally hard‑codes secrets to exercise automatic secret‑scanning tools like GitGuardian.
NEVER use these in real life.
"""
import base64
import mysql.connector
import requests
from cryptography.hazmat.primitives import serialization, hashes
from cryptography.hazmat.primitives.asymmetric import padding

# MySQL credentials
_DB_HOST = "db.example.org"
_DB_USER = "gg_py_user"
_DB_PASS = "S3cr3tPassw0rd!"
_DB_NAME = "gitguardian_demo"

# API tokens
_BEARER_TOKEN = (
    "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.cHl0aG9uLXRva2VuLnNhbXBsZQ.KuPlZz3eK0xU3wY4miuNQxP7JvJg9pP3vPonzxEz1LQ"
)
_API_KEY = "ak_test_python_a1b2c3d4e5"

# Slack bot token
_SLACK_BOT_TOKEN = "xoxb-098765432109-567890123456-abcDEFghiJKLmnopQRStuv"

# Private key (dummy)
_PRIVATE_KEY_PEM = b"""-----BEGIN RSA PRIVATE KEY-----
MIIEpAIBAAKCAQEA7g4j3BwFmRmZC7gqEp28ZjfC+TLqiT+v5M6wmaemJvhwnbtY
7HzO1vKcY7e5IyQD1bBi3vHYkfXjD4PV/9vc0Y6s54yzZK2Gq43by0eT46s9wuif
0iG6XEYQOA9GX1AnnS9+ELurB9UqKxVop1+V9jG5tdLocvFkn+F7SJcKuPjP8U/S
K9Ul3GflK7Fygw4x7wIDAQABAoIBAG3PBivFHGcf6jDnZFAyOrQ7ltR+v58FNeaX
...snip...
-----END RSA PRIVATE KEY-----"""

def connect_database():
    """Return an open MySQL connection."""
    return mysql.connector.connect(
        host=_DB_HOST, user=_DB_USER, password=_DB_PASS, database=_DB_NAME
    )

def list_users_bearer():
    """Call list‑users API using the Bearer token."""
    headers = {"Authorization": f"Bearer {_BEARER_TOKEN}"}
    return requests.get("https://api.example.org/rest/v2/list-users", headers=headers).text

def list_users_api_key():
    """Call list‑users API using an api_key query parameter."""
    return requests.get(
        "https://api.example.org/rest/v2/list-users", params={"api_key": _API_KEY}
    ).text

def auth_slack_bot() -> bool:
    """Return True if token looks like a Slack bot token."""
    return _SLACK_BOT_TOKEN.startswith("xoxb-")

def encrypt(plaintext: str) -> str:
    """Encrypt *plaintext* with the dummy RSA key and return base64."""
    private_key = serialization.load_pem_private_key(_PRIVATE_KEY_PEM, password=None)
    ciphertext = private_key.public_key().encrypt(
        plaintext.encode(),
        padding.PKCS1v15()
    )
    return base64.b64encode(ciphertext).decode()

if __name__ == "__main__":
    print(list_users_api_key()[:120] + "…")
