// gitguardian_test.cs
// ---------------------------------------------------
// Hard‑coded secrets for secret‑scanning tooling demo.
// DO NOT RE‑USE THESE SECRETS IN PRODUCTION.

using System;
using System.Net.Http;
using System.Security.Cryptography;
using System.Text;
using MySql.Data.MySqlClient;

namespace GitGuardianDemo
{
    public static class Secrets
    {
        // ───────────────────────────────
        // MySQL Credentials
        // ───────────────────────────────
        private const string DbHost = "db.example.org";
        private const string DbUser = "gg_cs_user";
        private const string DbPass = "P@55w0rdCS!";
        private const string DbName = "gitguardian_demo";

        // ───────────────────────────────
        // API Tokens
        // ───────────────────────────────
        private const string BearerToken = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.LmNzaGFycC10b2tlbg.sT7FDdPDc8mT0k1Qn3YYJu1Uu8VG5cy9A6rM0i7PV2k";
        private const string ApiKey      = "ak_live_csharp_d34db33f42";

        // ───────────────────────────────
        // Slack Bot Token
        // ───────────────────────────────
        private const string SlackBotToken = "xoxb-872364987234-297846912739-DFgHjkLmNoPqRSTUvWXyZA";

        // ───────────────────────────────
        // RSA Private Key (PEM)
        // ───────────────────────────────
        private const string PrivateKeyPem = @"-----BEGIN RSA PRIVATE KEY-----
MIIEpAIBAAKCAQEAtj1IrsFnqSOrNqQ3xQCgrt7/6iEHFqlK6rC94YZmQgc4vNVP
lQzXT+/6L1irmjqcosCSwH/rgv42B2GDLbgxQp60s+GtyjQl9yXsfZx1WmA/ty9T
UZnJdU/y+S9CN9hQ4JjVn6l7RXEWeU4Wc/qv1I3i/R2VVE2X4IVeXvGzNn9UxI4N
q1G+7l5ew+qAIp5vG/FwGqf8KFqPN8/2b1ZLTbQOD7DsT9BrSKKKk3jTzj4fWXBb
Z6a7Zr7xWgOK0l+GZ5Nt/eu3M/JdiG1MWdc1E8uNh3ETx5n3uN1xZzQ7CnW5t1YR
HoMcw74axlpXuph4hQKBMQtCXTtr1WdYl2vtgwIDAQABAoIBAFiOfOBuhTG/u5ww
-----END RSA PRIVATE KEY-----";

        // ───────────────────────────────
        // Database Connection
        // ───────────────────────────────
        public static MySqlConnection ConnectDatabase()
        {
            var connString = $"Server={DbHost};User ID={DbUser};Password={DbPass};Database={DbName};";
            var conn = new MySqlConnection(connString);
            conn.Open();
            return conn;
        }

        // ───────────────────────────────
        // API Calls
        // ───────────────────────────────
        public static string GetUsersWithBearer()
        {
            using var client = new HttpClient();
            client.DefaultRequestHeaders.Authorization =
                new System.Net.Http.Headers.AuthenticationHeaderValue("Bearer", BearerToken);
            return client.GetStringAsync("https://api.example.org/rest/v2/list-users").Result;
        }

        public static string GetUsersWithApiKey()
        {
            using var client = new HttpClient();
            var url = $"https://api.example.org/rest/v2/list-users?api_key={ApiKey}";
            return client.GetStringAsync(url).Result;
        }

        // ───────────────────────────────
        // Slack Bot Auth Test
        // ───────────────────────────────
        public static bool AuthSlackBot() => SlackBotToken.StartsWith("xoxb-");

        // ───────────────────────────────
        // Encryption Helper
        // ───────────────────────────────
        public static string Encrypt(string plaintext)
        {
            using var rsa = RSA.Create();
            rsa.ImportFromPem(PrivateKeyPem.ToCharArray());
            var bytes = Encoding.UTF8.GetBytes(plaintext);
            var encrypted = rsa.Encrypt(bytes, RSAEncryptionPadding.Pkcs1);
            return Convert.ToBase64String(encrypted);
        }
    }
}
