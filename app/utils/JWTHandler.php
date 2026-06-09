<?php

require_once 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTHandler
{
    private $secret_key;

    public function __construct()
    {
        // Secret key phải đủ dài để ký JWT bằng HS256
        $this->secret_key = 'HUTECH_THPTPMNM_SECRET_KEY_2026_BAI6_JWT_RESTFUL_API_SECURITY_123456789';
    }

    public function encode($data)
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600; // Token sống 1 giờ

        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'data' => $data
        ];

        return JWT::encode($payload, $this->secret_key, 'HS256');
    }

    public function decode($jwt)
    {
        try {
            $decoded = JWT::decode($jwt, new Key($this->secret_key, 'HS256'));
            return (array) $decoded->data;
        } catch (Exception $e) {
            return null;
        }
    }
}