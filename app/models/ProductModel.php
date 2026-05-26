<?php

require_once 'app/config/redis.php';

class ProductModel
{
    private $conn;
    private $table_name = "product";
    private $cache;

    // Cache keys
    const CACHE_ALL_PRODUCTS = 'products:all';
    const CACHE_PRODUCT_PREFIX = 'products:item:';

    public function __construct($db)
    {
        $this->conn = $db;
        $this->cache = RedisCache::getInstance();
    }

    public function getProducts()
    {
        // Thử lấy từ Redis cache trước
        $cached = $this->cache->get(self::CACHE_ALL_PRODUCTS);
        if ($cached !== null) {
            return $cached;
        }

        // Không có cache -> truy vấn database
        $query = "SELECT p.id, p.name, p.description, p.price, p.image, c.name AS category_name
                  FROM " . $this->table_name . " p
                  LEFT JOIN category c ON p.category_id = c.id
                  ORDER BY p.id DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $products = $stmt->fetchAll(PDO::FETCH_OBJ);

        // Lưu vào Redis cache (5 phút)
        $this->cache->set(self::CACHE_ALL_PRODUCTS, $products);

        return $products;
    }

    public function getProductById($id)
    {
        // Thử lấy từ cache
        $cacheKey = self::CACHE_PRODUCT_PREFIX . $id;
        $cached = $this->cache->get($cacheKey);
        if ($cached !== null) {
            return $cached;
        }

        $query = "SELECT p.*, c.name AS category_name
                  FROM " . $this->table_name . " p
                  LEFT JOIN category c ON p.category_id = c.id
                  WHERE p.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $product = $stmt->fetch(PDO::FETCH_OBJ);

        // Lưu vào cache (5 phút)
        if ($product) {
            $this->cache->set($cacheKey, $product);
        }

        return $product;
    }

    public function addProduct($name, $description, $price, $category_id, $image = "")
    {
        $errors = $this->validateProduct($name, $description, $price, $category_id);

        if (count($errors) > 0) {
            return $errors;
        }

        $query = "INSERT INTO " . $this->table_name . "
                  (name, description, price, image, category_id)
                  VALUES (:name, :description, :price, :image, :category_id)";

        $stmt = $this->conn->prepare($query);

        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $price = htmlspecialchars(strip_tags($price));
        $image = htmlspecialchars(strip_tags($image));
        $category_id = htmlspecialchars(strip_tags($category_id));

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':category_id', $category_id);

        $result = $stmt->execute();

        // Xóa cache khi thêm sản phẩm mới
        if ($result) {
            $this->clearProductCache();
        }

        return $result ? true : false;
    }

    public function updateProduct($id, $name, $description, $price, $category_id, $image = null)
    {
        $errors = $this->validateProduct($name, $description, $price, $category_id);

        if (count($errors) > 0) {
            return $errors;
        }

        if ($image !== null && $image !== '') {
            $query = "UPDATE " . $this->table_name . "
                      SET name = :name, description = :description, price = :price,
                          image = :image, category_id = :category_id
                      WHERE id = :id";
        } else {
            $query = "UPDATE " . $this->table_name . "
                      SET name = :name, description = :description, price = :price,
                          category_id = :category_id
                      WHERE id = :id";
        }

        $stmt = $this->conn->prepare($query);

        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $price = htmlspecialchars(strip_tags($price));
        $category_id = htmlspecialchars(strip_tags($category_id));

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id);

        if ($image !== null && $image !== '') {
            $image = htmlspecialchars(strip_tags($image));
            $stmt->bindParam(':image', $image);
        }

        $result = $stmt->execute();

        // Xóa cache khi cập nhật sản phẩm
        if ($result) {
            $this->clearProductCache();
            $this->cache->delete(self::CACHE_PRODUCT_PREFIX . $id);
        }

        return $result ? true : false;
    }

    public function deleteProduct($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        $result = $stmt->execute();

        // Xóa cache khi xóa sản phẩm
        if ($result) {
            $this->clearProductCache();
            $this->cache->delete(self::CACHE_PRODUCT_PREFIX . $id);
        }

        return $result ? true : false;
    }

    /**
     * Xóa tất cả cache liên quan đến sản phẩm
     */
    private function clearProductCache()
    {
        $this->cache->delete(self::CACHE_ALL_PRODUCTS);
        $this->cache->deleteByPrefix(self::CACHE_PRODUCT_PREFIX);
    }

    private function validateProduct($name, $description, $price, $category_id)
    {
        $errors = [];

        if (empty(trim($name))) {
            $errors['name'] = 'Tên sản phẩm không được để trống';
        } elseif (strlen(trim($name)) < 10 || strlen(trim($name)) > 100) {
            $errors['name'] = 'Tên sản phẩm phải có từ 10 đến 100 ký tự';
        }

        if (empty(trim($description))) {
            $errors['description'] = 'Mô tả không được để trống';
        }

        if (!is_numeric($price) || $price <= 0) {
            $errors['price'] = 'Giá sản phẩm phải lớn hơn 0';
        }

        if (empty($category_id)) {
            $errors['category_id'] = 'Vui lòng chọn danh mục';
        }

        return $errors;
    }
}
