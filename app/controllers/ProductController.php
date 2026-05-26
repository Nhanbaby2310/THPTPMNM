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

    // ===== BÀI 3: GIỎ HÀNG, ĐẶT HÀNG, THANH TOÁN =====

    public function addToCart($id)
    {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            echo "Không tìm thấy sản phẩm.";
            return;
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $_SESSION['cart'][$id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image
            ];
        }

        header('Location: ' . url('Product/cart'));
        exit();
    }

    public function cart()
    {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        include 'app/views/product/cart.php';
    }

    public function updateCart()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? '';
            $quantity = (int)($_POST['quantity'] ?? 1);

            if (isset($_SESSION['cart'][$id])) {
                if ($quantity <= 0) {
                    unset($_SESSION['cart'][$id]);
                } else {
                    $_SESSION['cart'][$id]['quantity'] = $quantity;
                }
            }
        }

        header('Location: ' . url('Product/cart'));
        exit();
    }

    public function removeFromCart($id)
    {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }

        header('Location: ' . url('Product/cart'));
        exit();
    }

    public function checkout()
    {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        include 'app/views/product/checkout.php';
    }

    public function processCheckout()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $address = $_POST['address'] ?? '';

            // Kiểm tra giỏ hàng
            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                echo "Giỏ hàng trống.";
                return;
            }

            // Bắt đầu giao dịch
            $this->db->beginTransaction();

            try {
                // Lưu thông tin đơn hàng vào bảng orders
                $query = "INSERT INTO orders (name, phone, address) VALUES (:name, :phone, :address)";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':address', $address);
                $stmt->execute();
                $order_id = $this->db->lastInsertId();

                // Lưu chi tiết đơn hàng vào bảng order_details
                $cart = $_SESSION['cart'];
                foreach ($cart as $product_id => $item) {
                    $query = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':order_id', $order_id);
                    $stmt->bindParam(':product_id', $product_id);
                    $stmt->bindParam(':quantity', $item['quantity']);
                    $stmt->bindParam(':price', $item['price']);
                    $stmt->execute();
                }

                // Xóa giỏ hàng sau khi đặt hàng thành công
                unset($_SESSION['cart']);

                // Commit giao dịch
                $this->db->commit();

                // Chuyển hướng đến trang xác nhận đơn hàng
                header('Location: ' . url('Product/orderConfirmation'));
                exit();
            } catch (Exception $e) {
                // Rollback giao dịch nếu có lỗi
                $this->db->rollBack();
                echo "Đã xảy ra lỗi khi xử lý đơn hàng: " . $e->getMessage();
            }
        }
    }

    public function orderConfirmation()
    {
        include 'app/views/product/orderConfirmation.php';
    }

    // ===== HELPER METHODS =====

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