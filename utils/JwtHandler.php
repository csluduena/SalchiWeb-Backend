<?php
require_once __DIR__ . '/../vendor/autoload.php';
use \Firebase\JWT\JWT;

class JwtHandler {
    private $secret;
    private $algorithm;

    public function __construct() {
        $this->secret = $_ENV['JWT_SECRET'];
        $this->algorithm = 'HS256';
    }

    public function generateToken($data) {
        $issuedAt = time();
        $expire = $issuedAt + 3600; // 1 hour

        $payload = [
            'iat' => $issuedAt,
            'exp' => $expire,
            'data' => $data
        ];

        return JWT::encode($payload, $this->secret, $this->algorithm);
    }

    public function validateToken($token) {
        try {
            $decoded = JWT::decode($token, $this->secret, [$this->algorithm]);
            return (array) $decoded->data;
        } catch (Exception $e) {
            return false;
        }
    }
}

