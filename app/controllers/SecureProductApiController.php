<?php

require_once 'app/config/database.php';
require_once 'app/models/ProductModel.php';
require_once 'app/utils/JWTHandler.php';

class SecureProductApiController
{
    private $db;
    private $productModel;
    private $jwtHandler;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();

        if (!$this->db) {
            $this->jsonResponse(['message' => 'Database connection failed'], 500);
            exit;
        }

        $this->productModel = new ProductModel($this->db);
        $this->jwtHandler = new JWTHandler();
    }

    private function authenticate()
    {
        $headers = function_exists('getallheaders') ? getallheaders() : [];

        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $this->jsonResponse(['message' => 'Unauthorized. Missing token.'], 401);
            exit;
        }

        $token = $matches[1];
        $decoded = $this->jwtHandler->decode($token);

        if (!$decoded) {
            $this->jsonResponse(['message' => 'Unauthorized. Invalid or expired token.'], 401);
            exit;
        }

        return $decoded;
    }

    private function requireAdmin()
    {
        $user = $this->authenticate();

        if (($user['role'] ?? '') !== 'admin') {
            $this->jsonResponse(['message' => 'Forbidden. Admin only.'], 403);
            exit;
        }

        return $user;
    }

    public function index()
    {
        $this->authenticate();

        $products = $this->productModel->getProducts();
        $this->jsonResponse($products);
    }

    public function show($id)
    {
        $this->authenticate();

        $product = $this->productModel->getProductById($id);

        if ($product) {
            $this->jsonResponse($product);
        } else {
            $this->jsonResponse(['message' => 'Product not found'], 404);
        }
    }

    public function store()
    {
        $this->requireAdmin();

        $data = json_decode(file_get_contents('php://input'), true);

        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';
        $price = $data['price'] ?? '';
        $category_id = $data['category_id'] ?? null;
        $image = $data['image'] ?? '';

        $result = $this->productModel->addProduct($name, $description, $price, $category_id, $image);

        if (is_array($result)) {
            $this->jsonResponse(['errors' => $result], 400);
        } elseif ($result) {
            $this->jsonResponse(['message' => 'Product created successfully'], 201);
        } else {
            $this->jsonResponse(['message' => 'Product creation failed'], 500);
        }
    }

    public function update($id)
    {
        $this->requireAdmin();

        $data = json_decode(file_get_contents('php://input'), true);

        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';
        $price = $data['price'] ?? '';
        $category_id = $data['category_id'] ?? null;
        $image = $data['image'] ?? '';

        $result = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $image);

        if (is_array($result)) {
            $this->jsonResponse(['errors' => $result], 400);
        } elseif ($result) {
            $this->jsonResponse(['message' => 'Product updated successfully']);
        } else {
            $this->jsonResponse(['message' => 'Product update failed'], 500);
        }
    }

    public function destroy($id)
    {
        $this->requireAdmin();

        $result = $this->productModel->deleteProduct($id);

        if ($result) {
            $this->jsonResponse(['message' => 'Product deleted successfully']);
        } else {
            $this->jsonResponse(['message' => 'Product deletion failed'], 500);
        }
    }

    private function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}