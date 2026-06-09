<?php

require_once 'app/config/database.php';
require_once 'app/models/ProductModel.php';

class ProductApiController
{
    private $db;
    private $productModel;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();

        if (!$this->db) {
            $this->jsonResponse(['message' => 'Database connection failed'], 500);
            exit;
        }

        $this->productModel = new ProductModel($this->db);
    }

    // GET /api/product
    public function index()
    {
        $products = $this->productModel->getProducts();
        $this->jsonResponse($products);
    }

    // GET /api/product/{id}
    public function show($id)
    {
        $product = $this->productModel->getProductById($id);

        if ($product) {
            $this->jsonResponse($product);
        } else {
            $this->jsonResponse(['message' => 'Product not found'], 404);
        }
    }

    // POST /api/product
    public function store()
    {
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

    // PUT /api/product/{id}
    public function update($id)
    {
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

    // DELETE /api/product/{id}
    public function destroy($id)
    {
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