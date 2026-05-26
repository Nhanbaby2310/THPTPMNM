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
            $payment_method = $_POST['payment_method'] ?? 'cod';

            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                echo "Giỏ hàng trống.";
                return;
            }

            // Tính tổng tiền
            $total = 0;
            foreach ($_SESSION['cart'] as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            // Lưu đơn hàng vào database
            $this->db->beginTransaction();

            try {
                $query = "INSERT INTO orders (name, phone, address, payment_method, payment_status) VALUES (:name, :phone, :address, :payment_method, :payment_status)";
                $stmt = $this->db->prepare($query);
                $payment_status = ($payment_method == 'vnpay') ? 'pending' : 'cod';
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':address', $address);
                $stmt->bindParam(':payment_method', $payment_method);
                $stmt->bindParam(':payment_status', $payment_status);
                $stmt->execute();
                $order_id = $this->db->lastInsertId();

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

                $this->db->commit();

                // Nếu chọn VNPay -> redirect sang cổng thanh toán VNPay
                if ($payment_method == 'vnpay') {
                    $_SESSION['pending_order_id'] = $order_id;
                    $this->createVnpayPayment($order_id, $total);
                    return;
                }

                // COD: xóa giỏ hàng và chuyển trang xác nhận
                unset($_SESSION['cart']);
                header('Location: ' . url('Product/orderConfirmation'));
                exit();
            } catch (Exception $e) {
                $this->db->rollBack();
                echo "Đã xảy ra lỗi khi xử lý đơn hàng: " . $e->getMessage();
            }
        }
    }

    // ===== VNPAY PAYMENT =====

    private function createVnpayPayment($order_id, $total)
    {
        require_once 'app/config/vnpay_config.php';

        $vnp_TmnCode = VNPAY_TMN_CODE;
        $vnp_HashSecret = VNPAY_HASH_SECRET;
        $vnp_Url = VNPAY_URL;

        // Xây dựng Return URL động
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $vnp_ReturnUrl = $protocol . '://' . $host . url('Product/vnpayReturn');

        $vnp_TxnRef = $order_id . '_' . time();
        $vnp_OrderInfo = 'Thanh toan don hang #' . $order_id;
        $vnp_Amount = $total * 100; // VNPay yêu cầu nhân 100

        $inputData = array(
            "vnp_Version" => VNPAY_VERSION,
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => VNPAY_COMMAND,
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => VNPAY_CURR_CODE,
            "vnp_IpAddr" => $this->getClientIp(),
            "vnp_Locale" => VNPAY_LOCALE,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => VNPAY_ORDER_TYPE,
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url = $vnp_Url . "?" . $query . 'vnp_SecureHash=' . $vnpSecureHash;

        header('Location: ' . $vnp_Url);
        exit();
    }

    public function vnpayReturn()
    {
        require_once 'app/config/vnpay_config.php';

        $vnp_HashSecret = VNPAY_HASH_SECRET;
        $inputData = array();
        $vnp_SecureHash = $_GET['vnp_SecureHash'] ?? '';

        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']);

        ksort($inputData);
        $hashData = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        $vnp_ResponseCode = $_GET['vnp_ResponseCode'] ?? '';
        $vnp_TxnRef = $_GET['vnp_TxnRef'] ?? '';

        // Lấy order_id từ TxnRef (format: orderid_timestamp)
        $order_id = explode('_', $vnp_TxnRef)[0];

        if ($secureHash == $vnp_SecureHash) {
            if ($vnp_ResponseCode == '00') {
                // Thanh toán thành công
                $query = "UPDATE orders SET payment_status = 'paid' WHERE id = :id";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':id', $order_id);
                $stmt->execute();

                // Xóa giỏ hàng
                unset($_SESSION['cart']);
                unset($_SESSION['pending_order_id']);

                $payment_success = true;
                $message = "Thanh toan thanh cong! Don hang #$order_id da duoc xac nhan.";
            } else {
                // Thanh toán thất bại
                $payment_success = false;
                $message = "Thanh toan that bai. Ma loi: $vnp_ResponseCode";
            }
        } else {
            // Chữ ký không hợp lệ
            $payment_success = false;
            $message = "Chu ky khong hop le. Giao dich bi nghi ngo gia mao.";
        }

        include 'app/views/product/vnpay_return.php';
    }

    private function getClientIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        }
    }

    public function orderConfirmation()
    {
        include 'app/views/product/orderConfirmation.php';
    }
}