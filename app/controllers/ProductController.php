<?php

require_once 'app/config/database.php';
require_once 'app/models/ProductModel.php';
require_once 'app/models/CategoryModel.php';

class ProductController
{
    private $productModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();

        if (!$this->db) {
            die('Không kết nối được database. Hãy chạy file setup_database.php trước.');
        }

        $this->productModel = new ProductModel($this->db);
    }

    public function index()
    {
        $products = $this->productModel->getProducts();
        include 'app/views/product/list.php';
    }

    public function list()
    {
        $this->index();
    }

    public function show($id)
    {
        $product = $this->productModel->getProductById($id);
        $availableImages = $this->getAvailableImages();

        if ($product) {
            include 'app/views/product/show.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    public function add()
    {
        $categories = (new CategoryModel($this->db))->getCategories();
        $availableImages = $this->getAvailableImages();

        include 'app/views/product/add.php';
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;

            // Chọn ảnh có sẵn trong public/uploads nếu có
            $image = $_POST['existing_image'] ?? '';

            // Nếu người dùng upload ảnh mới thì ưu tiên ảnh mới
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $uploadedImage = $this->uploadImage($_FILES['image']);

                if (!empty($uploadedImage)) {
                    $image = $uploadedImage;
                }
            }

            $result = $this->productModel->addProduct($name, $description, $price, $category_id, $image);

            if (is_array($result)) {
                $errors = $result;
                $categories = (new CategoryModel($this->db))->getCategories();
                $availableImages = $this->getAvailableImages();

                include 'app/views/product/add.php';
            } else {
                header('Location: ' . url('Product'));
                exit();
            }
        }
    }

    public function edit($id)
    {
        $product = $this->productModel->getProductById($id);
        $categories = (new CategoryModel($this->db))->getCategories();
        $availableImages = $this->getAvailableImages();

        if ($product) {
            include 'app/views/product/edit.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? '';
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;

            // Ảnh có sẵn từ dropdown
            $image = $_POST['existing_image'] ?? null;

            // Nếu upload ảnh mới thì ưu tiên ảnh mới
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $uploadedImage = $this->uploadImage($_FILES['image']);

                if (!empty($uploadedImage)) {
                    $image = $uploadedImage;
                }
            }

            $result = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $image);

            if (is_array($result)) {
                $errors = $result;
                $product = $this->productModel->getProductById($id);
                $categories = (new CategoryModel($this->db))->getCategories();
                $availableImages = $this->getAvailableImages();

                include 'app/views/product/edit.php';
            } else {
                header('Location: ' . url('Product'));
                exit();
            }
        }
    }

    public function delete($id)
    {
        if ($this->productModel->deleteProduct($id)) {
            header('Location: ' . url('Product'));
            exit();
        }

        echo "Đã xảy ra lỗi khi xóa sản phẩm.";
    }

    private function uploadImage($file)
    {
        $targetDir = "public/uploads/";

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $imageFileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($imageFileType, $allowedTypes)) {
            return "";
        }

        if ($file["size"] > 5 * 1024 * 1024) {
            return "";
        }

        $fileName = time() . "_" . uniqid() . "." . $imageFileType;
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            return $targetFile;
        }

        return "";
    }

    private function getAvailableImages()
    {
        $imageDir = "public/uploads/";
        $images = [];

        if (is_dir($imageDir)) {
            $files = scandir($imageDir);

            foreach ($files as $file) {
                if ($file == "." || $file == ".." || $file == ".gitkeep") {
                    continue;
                }

                $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                if (in_array($extension, $allowedTypes)) {
                    $images[] = $imageDir . $file;
                }
            }
        }

        return $images;
    }
}