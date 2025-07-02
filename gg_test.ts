/**
 * gitguardian_test.ts
 * ---------------------------------------------------
 * Intentionally embeds hard‑coded secrets for automated secret‑scanners.
 * NEVER commit real secrets!
 */
import mysql from 'mysql2/promise';
import axios from 'axios';
import crypto from 'crypto';

// MySQL
const DB_HOST = 'db.example.org';
const DB_USER = 'gg_ts_user';\...
