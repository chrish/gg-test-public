/*
 * gitguardian_test.java
 * ---------------------------------------------------
 * Demonstrates hard‑coded secrets for secret‑scan testing.
 * DO NOT DEPLOY THESE SECRETS.
 */
import java.net.http.*;
import java.net.URI;
import java.sql.*;
import java.util.Base64;
import javax.crypto.Cipher;
import java.security.*;
import java.security.spec.PKCS8EncodedKeySpec;

public class GitGuardianTest {
    // MySQL
    private static final String DB_HOST = "db.example.org";
    private static final String DB_USER = "gg_java_user";
    private static final String DB_PASS = "j4v4P@ssw0rd!";
    private static final String DB_NAME = "gitguardian_demo";

    // API tokens
    private static final String BEARER_TOKEN = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.amF2YS1zYW1wbGU.UqEw0L2LQ2PYEbqgVzw4Wk8Nga8qdk-WOqi0eYtXO34";
    private static final String API_KEY     = "ak_live_java_f00dbabe42";

    // Slack
    private static final String SLACK_BOT_TOKEN = "xoxb-556677889900-112233445566-OpQrStUvWxYzABcDEfGhIJ";

    // RSA Private Key (dummy but correct format)
    private static final String PRIVATE_KEY_PEM = """
-----BEGIN RSA PRIVATE KEY-----
MIIEpQIBAAKCAQEAvAC3Frbg9FrbX2kXh3W/Sjm8HTuKzj5mMUbZpcx9ZCoWObiy
qnBQOsk3lqE9HeD98qmcUNHvb+6ccDRtejzm8vFjKKeKfX8EIfvQ5u6IzHaSyPH/
4Rr2kkjwSMQ1xxlp+0eKTIFkMdMu5VaHKJTX87dZ4UuIcbXMDvKkCSvd3LHZ/1xx
W6+2FWoaJp7tzDyog+E9/byCGRjFyt7fk+hR9yjs3IACn+uCM1JBodOZAAtjdpnB
yz/dm+R6u8YhVqAZ81/5CD31Ooff0xsO+/Hyxv1/5eW0Cu0+3eiMiZvGmZ7+QXwK
PWPR4d+N2/jlOexYWbWHD7ex2MaiJz+U6wIDAQABAoIBAQC4N8bl2EQRkxkXszyo
...
-----END RSA PRIVATE KEY-----""";

    public static Connection connectDatabase() throws SQLException {
        String url = "jdbc:mysql://" + DB_HOST + "/" + DB_NAME;
        return DriverManager.getConnection(url, DB_USER, DB_PASS);
    }

    public static String listUsersBearer() throws Exception {
        HttpClient client = HttpClient.newHttpClient();
        HttpRequest request = HttpRequest.newBuilder()
                .uri(URI.create("https://api.example.org/rest/v2/list-users"))
                .header("Authorization", "Bearer " + BEARER_TOKEN)
                .build();
        return client.send(request, HttpResponse.BodyHandlers.ofString()).body();
    }

    public static String listUsersApiKey() throws Exception {
        HttpClient client = HttpClient.newHttpClient();
        HttpRequest request = HttpRequest.newBuilder()
                .uri(URI.create("https://api.example.org/rest/v2/list-users?api_key=" + API_KEY))
                .build();
        return client.send(request, HttpResponse.BodyHandlers.ofString()).body();
    }

    public static boolean authSlackBot() {
        return SLACK_BOT_TOKEN.startsWith("xoxb-");
    }

    public static String encrypt(String plaintext) throws Exception {
        String pem = PRIVATE_KEY_PEM.replace("-----BEGIN RSA PRIVATE KEY-----", "")
                                    .replace("-----END RSA PRIVATE KEY-----", "")
                                    .replaceAll("\\s", "");
        byte[] keyBytes = Base64.getDecoder().decode(pem);
        KeyFactory kf = KeyFactory.getInstance("RSA");
        PrivateKey privateKey = kf.generatePrivate(new PKCS8EncodedKeySpec(keyBytes));
        Cipher cipher = Cipher.getInstance("RSA/ECB/PKCS1Padding");
        cipher.init(Cipher.ENCRYPT_MODE, privateKey);
        byte[] cipherText = cipher.doFinal(plaintext.getBytes());
        return Base64.getEncoder().encodeToString(cipherText);
    }
}
