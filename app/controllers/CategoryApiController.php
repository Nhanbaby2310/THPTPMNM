<?php

require_once 'app/config/database.php';
require_once 'app/models/CategoryModel.php';

class CategoryApiController
{
    private $db;
    private $categoryModel;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();

        if (!$this->db) {
            $this->jsonResponse(['message' => 'Database connection failed'], 500);
            exit;
        }

        $this->categoryModel = new CategoryModel($this->db);
    }

    // GET /api/category
    public function index()
    {
        $categories = $this->categoryModel->getCategories();
        $this->jsonResponse($categories);
    }

    private function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}