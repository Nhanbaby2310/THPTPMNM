<?php
$host = "localhost";
$username = "root";
$password = "";
$dbName = "my_store";

try {
    $conn = new PDO("mysql:host=$host", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "
    CREATE DATABASE IF NOT EXISTS $dbName CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    USE $dbName;

    CREATE TABLE IF NOT EXISTS category (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        description TEXT
    );

    CREATE TABLE IF NOT EXISTS product (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        description TEXT,
        price DECIMAL(10,2) NOT NULL,
        image VARCHAR(255) DEFAULT NULL,
        category_id INT,
        FOREIGN KEY (category_id) REFERENCES category(id) ON DELETE CASCADE
    );
    ";

    $conn->exec($sql);

    $count = $conn->query("SELECT COUNT(*) FROM category")->fetchColumn();

    if ($count == 0) {
        $conn->exec("
            INSERT INTO category (name, description) VALUES
            ('Điện thoại', 'Danh mục các loại điện thoại'),
            ('Laptop', 'Danh mục các loại laptop'),
            ('Máy tính bảng', 'Danh mục các loại máy tính bảng'),
            ('Phụ kiện', 'Danh mục phụ kiện điện tử'),
            ('Thiết bị âm thanh', 'Danh mục loa, tai nghe, micro')
        ");
    }

    echo "<h1>Đã tạo database thành công!</h1>";
    echo "<p>Database: <strong>my_store</strong></p>";
    echo "<p>Đã có bảng <strong>category</strong> và <strong>product</strong>.</p>";
    echo "<a href='index.php'>Về trang chủ</a>";
} catch (PDOException $e) {
    echo "Lỗi tạo database: " . $e->getMessage();
}
