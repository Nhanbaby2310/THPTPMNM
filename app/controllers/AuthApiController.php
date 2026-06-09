<?php

require_once 'app/config/database.php';
require_once 'app/models/AccountModel.php';
require_once 'app/utils/JWTHandler.php';

class AuthApiController
{
    private $db;
    private $accountModel;
    private $jwtHandler;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();

        if (!$this->db) {
            $this->jsonResponse(['message' => 'Database connection failed'], 500);
            exit;
        }

        $this->accountModel = new AccountModel($this->db);
        $this->jwtHandler = new JWTHandler();
    }

    // POST /api/auth/login
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['message' => 'Method not allowed'], 405);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        if (trim($username) === '' || trim($password) === '') {
            $this->jsonResponse(['message' => 'Username and password are required'], 400);
            return;
        }

        $account = $this->accountModel->getAccountByUsername($username);

        if (!$account || !password_verify($password, $account->password)) {
            $this->jsonResponse(['message' => 'Invalid username or password'], 401);
            return;
        }

        $token = $this->jwtHandler->encode([
            'id' => $account->id,
            'username' => $account->username,
            'fullname' => $account->fullname,
            'role' => $account->role
        ]);

        $this->jsonResponse([
            'message' => 'Login successful',
            'token' => $token,
            'user' => [
                'id' => $account->id,
                'username' => $account->username,
                'fullname' => $account->fullname,
                'role' => $account->role
            ]
        ]);
    }

    private function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}