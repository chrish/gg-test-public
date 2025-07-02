<?php
/**
 * gitguardian_test.php
 * ----------------------------------
 * This file intentionally contains hard‑coded secrets for testing secret‑scanning tools such as GitGuardian.
 * DO NOT USE THESE SECRETS IN PRODUCTION.
 */

declare(strict_types=1);

// ─────────────────────────────────────────────────────────────────────────────
// Database credentials (MySQL)
// ─────────────────────────────────────────────────────────────────────────────
const DB_HOST = 'db.example.org';
const DB_USER = 'gg_admin';
const DB_PASS = 'T#1s1sASup3rS3cr3tP@ssw0rd!';
const DB_NAME = 'exampledb';

// ─────────────────────────────────────────────────────────────────────────────
// API secrets
// ─────────────────────────────────────────────────────────────────────────────
const API_BASE_URL   = 'https://api.example.org/rest/v2/list-users';
const BEARER_TOKEN   = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJiZmQ2NmMwYS1lYzZiLTQxYTgtYWQ0YS0zZWVlZDkxNjhiZGUiLCJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkdpdEd1YXJkaWFuIFRlc3QiLCJpYXQiOjE2OTk4NDU5OTIsImV4cCI6MTk4Nzg0NTk5Mn0.piCIrVitT0QG7Rv3bd6-HWlCf3GyB7z3ntmOq6Uu8Fg'; // Looks like a JWT
const API_KEY        = 'sk_live_51JH4Mc7YdEr8F2o3LqSpPn1o3m5y2kYlDpLxq1KjBZD4yHpNZE9aA8TjIWN0I1jHJ05nYDsP4lHuBq9aPUY3qG8'; // Stripe‑style live key

// ─────────────────────────────────────────────────────────────────────────────
// Slack bot token
// ─────────────────────────────────────────────────────────────────────────────
const SLACK_BOT_TOKEN = 'xoxb-483920313216-2384930123448-G5i4r8h6KleN8c2hB7eQf6D9';

// ─────────────────────────────────────────────────────────────────────────────
// Private key for encryption (RSA 2048, dummy)
// ─────────────────────────────────────────────────────────────────────────────
const PRIVATE_KEY_PEM = <<<'PEM'
-----BEGIN PRIVATE KEY-----
MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDZ43vdCG3F3unc
AVxuwx7i5m3KX2GvyqE7Uxpcz6SwT2gV4PNgFDBeqOwpSkTS3xfwZ+dlxRv9dtmi
P3JR2/8Xhi3eN1GNuT9uEB1Fx+MNx1ufAv/+9Tr79dCLRqNGQVQxLYEk+DZgHiG9
jMN5GbsoEE7lvSXpIczYdCiXyBD2GcXSWOVzm13opqkM3pwp+VlxtuHNxqisrAag
4P2x1xoGLxCLKgQrqnl+mYpUXRznHBPwv0Map2LkUS1j+UJnSJZq3H0bwBzZTgyo
KQz+/4v5a6XaI6iWAttIKAMy4apwKcohO0KkPy57Lwy4tTaUg8qs9JD1prMkbZm9
+QKCyjzlAgMBAAECggEACXm5VpnBtQGCfKOPfj+cLe9s+RVnqiwnejBOVn8Vh2tZ
vPW08Uk3DYnSpwWjTNYmVQNuyZ7fxp3OGAw7MSLzM9ITxVNQz9IgAZxWVfg9tKCV
qGfXgppV4KhdMVQ+NAPY+QeKybChX03nYxVk7QuP47Mek4NvWjdUwb9LXYX4YGPx
HfS83279Uy490AwNnpsXYYzoh7XzxKidjdBceUfRZdnrd7I+w2hEFxJ8pirybf6Q
pVJGHT3fb5OBAqLjk6ZqD/A88jJxY1l2MlbU4bEzwQJ1I9qtm/WXhT7sm6zaculm
RCL2hcBxaYqk0YLYxpz6FHCEv74DdgvWNmXg11fNYQKBgQD7g28+LnNmLDNbIjAQ
4WtWZjuuPPsZ6sci+VttU7dRCfpLIWibXYHh1t8AHno7lIrlQVYZ0Dloj3wi292U
RW/7C+QFmp1vXdLbCevLvn9imicbnBxwkGzx6h0zN+bntFUhrS0AN2f/6I6upIuF
LYBiBge6Oz4ltq4Qma0bbESNNQKBgQDaFAX9wyB6xObBbf+yEZhRX5LfJzmEIVQy
Nrv+0ALsVypo4oIrCVA8kdt7OqwBG8E8F0UT1evsn7M7Bus1Qr5HTmxnw7qkiw1V
jTJc4ybNUyxFkZEHwsIrR5j7VhZ2W9aarTIxGP2PH0jqqdqdWJ4LLW/9H27InQB+
X30NvZ9FCQKBgFPoZGKaunnPfQZpomoAeUkUTy/zbGrgqh+uO0alvLq5yHeQX3WK
l1p6t8mG03Z8T1nfs9ZBktmx3CMWLygxds/K4W4eKVZ6iw5OtTcO2gX8TDLCiyas
Dj6OeHBJBHY97q4Q0PjrbhZhejWRm2c3ZHulLQbkw+N1bAykgoDDrY/RAoGBALhQ
CfG0AKEWU1XrO9ZB8x+iFa/YosvWp56uI7fdL5gH9xftD144j7eaR2YVovgXUVYS
rO/xwxp7Zdp3BHs+Jaq4uzBcfu6dPd8Lm7nDYf4Y6XZ6Jhcz21oJM3D3ZaQBHM/j
uO0mxlPKTbUXlCyhNMQeMDGA9RDn67Jcmp1JffhhAoGBAM/kuISM1SutOebuwb4P
aGVN2UycXEvF9YuKkfwU5plNCgVZSGyEr1dEcCV+yh38kAhna2YF3KVycCZUI3JJ
B+F8CrFsbPLZ90RD5VPPLB0+Wqb98xEYvThyFRoR6nYteaXtZ8Don6MnWV4sf1Lp
spuO7dLVyADdQ1sZOa4d6HXH
-----END PRIVATE KEY-----
PEM;

// ─────────────────────────────────────────────────────────────────────────────
// Database connection
// ─────────────────────────────────────────────────────────────────────────────
function db_connect(): mysqli
{
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($mysqli->connect_error) {
        throw new RuntimeException('MySQL connection failed: ' . $mysqli->connect_error);
    }

    return $mysqli;
}

// ─────────────────────────────────────────────────────────────────────────────
// API calls
// ─────────────────────────────────────────────────────────────────────────────
/**
 * Retrieve the user list using a Bearer token.
 */
function listUsersWithBearer(): string
{
    $ch = curl_init(API_BASE_URL);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . BEARER_TOKEN,
            'Accept: application/json',
        ],
    ]);

    $response = curl_exec($ch);

    if ($response === false) {
        throw new RuntimeException('cURL error: ' . curl_error($ch));
    }

    curl_close($ch);

    return $response;
}

/**
 * Retrieve the user list using an API key as query parameter.
 */
function listUsersWithApiKey(): string
{
    $url = API_BASE_URL . '?apikey=' . rawurlencode(API_KEY);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => ['Accept: application/json'],
    ]);

    $response = curl_exec($ch);

    if ($response === false) {
        throw new RuntimeException('cURL error: ' . curl_error($ch));
    }

    curl_close($ch);

    return $response;
}

// ─────────────────────────────────────────────────────────────────────────────
// Slack bot authentication
// ─────────────────────────────────────────────────────────────────────────────
/**
 * Perform a simple auth.test call to verify the Slack token.
 */
function slackAuthTest(): array
{
    $ch = curl_init('https://slack.com/api/auth.test');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . SLACK_BOT_TOKEN,
            'Content-Type: application/x-www-form-urlencoded',
        ],
    ]);

    $response = curl_exec($ch);

    if ($response === false) {
        throw new RuntimeException('Slack API error: ' . curl_error($ch));
    }

    curl_close($ch);

    return json_decode($response, true, flags: JSON_THROW_ON_ERROR);
}

// ─────────────────────────────────────────────────────────────────────────────
// Encryption helper
// ─────────────────────────────────────────────────────────────────────────────
/**
 * Encrypt arbitrary data using the hard‑coded RSA private key.
 *
 * @param string $data Plain text data to encrypt.
 * @return string Base64‑encoded encrypted payload.
 */
function encryptWithPrivateKey(string $data): string
{
    $privateKey = openssl_pkey_get_private(PRIVATE_KEY_PEM);

    if ($privateKey === false) {
        throw new RuntimeException('Invalid private key.');
    }

    if (!openssl_private_encrypt($data, $encrypted, $privateKey)) {
        throw new RuntimeException('OpenSSL encryption failed: ' . openssl_error_string());
    }

    openssl_free_key($privateKey);

    return base64_encode($encrypted);
}

// ─────────────────────────────────────────────────────────────────────────────
// Example usage block (comment out in real deployments)
// ─────────────────────────────────────────────────────────────────────────────
if (php_sapi_name() === 'cli' && in_array('--demo', $argv, true)) {
    echo "Connecting to DB...\n";
    $db = db_connect();
    echo "DB server version: " . $db->server_info . "\n\n";

    echo "Fetching users with bearer token...\n";
    echo listUsersWithBearer() . "\n\n";

    echo "Fetching users with API key...\n";
    echo listUsersWithApiKey() . "\n\n";

    echo "Testing Slack auth...\n";
    print_r(slackAuthTest());

    echo "Encrypting sample message...\n";
    echo encryptWithPrivateKey('GitGuardian FTW!') . "\n";
}
