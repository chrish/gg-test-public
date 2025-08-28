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
        private const string SlackBotToken = "xoxb-872264987234-297846912739-DFgHjkLmNoPqRSTUvWXyZA";

        // ───────────────────────────────
        // RSA Private Key (PEM)
        // ───────────────────────────────
        private const string PrivateKeyPem = @"-----BEGIN OPENSSH PRIVATE KEY-----
b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW
QyNTUxOQAAACDs/lxfO/x8IACGljODM502QxsMu1r4gLRoF1Ccyt+DfwAAAKgUPVROFD1U
TgAAAAtzc2gtZWQyNTUxOQAAACDs/lxfO/x8IACGljODM502QxsMu1r4gLRoF1Ccyt+Dfw
AAAEA0OmIQpeKZHwaB4ts2goV4zkRcAiCO0GWlRpymqQOc8uz+XF87/HwgAIaWM4MznTZD
Gwy7WviAtGgXUJzK34N/AAAAIGNocmlzdG9mZmVyLmhhZnNhaGxATS1YWDU3UUdDSFhLAQ
IDBAU=
-----END OPENSSH PRIVATE KEY-----";

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
