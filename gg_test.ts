/**
 * gitguardian_test_full.ts
 * ---------------------------------------------------
 * This script intentionally embeds hard‑coded secrets for
 * secret‑scanner testing. NEVER commit real secrets!
 *
 * Dependencies (install via npm):
 *   npm i mysql2 axios @slack/web-api
 */

import mysql from 'mysql2/promise';
import axios from 'axios';
import { WebClient } from '@slack/web-api';
import crypto from 'crypto';

// ─────────────────────────────────────────────────────────────
// Hard‑coded secrets (throw‑away)
// ─────────────────────────────────────────────────────────────
const DB_HOST = 'db.example.org';
const DB_USER = 'gg_ts_user';
const DB_PASS = 'TsS3cr3t!P@ss';
const DB_NAME = 'gg_ts_db';
const DB_PORT = 3306;

const DB_URI = `mysql://${DB_USER}:${DB_PASS}@${DB_HOST}:${DB_PORT}/${DB_NAME}`;

const API_BASE = 'https://api.example.org/rest/v2/list-users';
const BEARER_TOKEN = 'eyJhbGciOiJSUzI1NiIsImtpZCI6IjEyMzQ1NiJ9.dummy.payload.sig';
const API_KEY = 'ggts_api_key_abcdef1234567890';

const SLACK_BOT_TOKEN = 'xoxb-123456781234-876543218765-iABCDEFGHIJKLMNopqrst';

const PRIVATE_KEY_PEM = `-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEAwF8WDNLj0uYQwq4mjbdJUiFVa4qOJufsdEjsA+/KJ1sxgMJd
z4tZFLm1xoKX+U6K6VUfiY4iCLFDGR+s3MjBuP2p4A2jQnMxR9ZdeHp4gXDcKIrX
CyI+JwhzLxe5H8/HaXqqE4+EZvN6ZlKyXLswFtUAl2yO3ZETiR0v4oj+WNEzX1wN
5urbeKDYMhd1+wGK7hIUX76ImyjjXzzBzS95nLQcTM6JWyf3lUu7dEUaGHPpnnE+
sUOfrZa7rGkHPKDlQNc1pgwN2I7R0tKx28cFz8nVaxHszGsxszO8fyIgU7k1Tfcv
ujEASAzwMWS/KZ0ojm99n6gZCTPe5Hn7JhzzzwIDAQABAoIBAHZJ8fmyGLWzCtBi
084N2+JhAPF1Bv5qYxx6Af2iHoTY55Gr9dqQXfqCMB7s7V3ZEL+ctiRqgTy30wEP
...shortened...
-----END RSA PRIVATE KEY-----`;

// ─────────────────────────────────────────────────────────────
// Database interaction
// ─────────────────────────────────────────────────────────────
export async function connectDb() {
  const pool = mysql.createPool({
    uri: DB_URI,
    waitForConnections: true,
    connectionLimit: 5,
  });
  const [rows] = await pool.query('SELECT VERSION() as version');
  console.log('[DB] Server version:', (rows as any)[0].version);
  return pool;
}

// ─────────────────────────────────────────────────────────────
// API interactions
// ─────────────────────────────────────────────────────────────
export async function listUsersBearer() {
  const resp = await axios.get(API_BASE, {
    headers: { Authorization: `Bearer ${BEARER_TOKEN}` },
  });
  console.log('[API] /list-users (Bearer) status:', resp.status);
  return resp.data;
}

export async function listUsersApiKey() {
  const resp = await axios.get(API_BASE, { params: { apikey: API_KEY } });
  console.log('[API] /list-users (apikey) status:', resp.status);
  return resp.data;
}

// ─────────────────────────────────────────────────────────────
// Slack bot authentication
// ─────────────────────────────────────────────────────────────
export async function slackAuth() {
  const client = new WebClient(SLACK_BOT_TOKEN);
  const auth = await client.auth.test();
  console.log('[Slack] Auth success for workspace:', auth.url);
  return auth;
}

// ─────────────────────────────────────────────────────────────
// Encryption helper
// ─────────────────────────────────────────────────────────────
export function encryptWithPrivateKey(message: string): string {
  const privateKey = crypto.createPrivateKey(PRIVATE_KEY_PEM);
  const encrypted = crypto.privateEncrypt(privateKey, Buffer.from(message));
  return encrypted.toString('base64');
}

// ─────────────────────────────────────────────────────────────
// Smoke‑test when executed directly
// ─────────────────────────────────────────────────────────────
async function main() {
  await connectDb();

  const usersBearer = await listUsersBearer();
  console.log('[API] First 2 users via Bearer:', usersBearer.slice(0, 2));

  const usersKey = await listUsersApiKey();
  console.log('[API] First 2 users via API key:', usersKey.slice(0, 2));

  await slackAuth();

  console.log('[Encrypt] sample:', encryptWithPrivateKey('Hello GitGuardian!'));
}

if (require.main === module) {
  main().catch(err => {
    console.error(err);
    process.exit(1);
  });
}
