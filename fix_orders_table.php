<?php
// File này chạy 1 lần để thêm cột payment_method và payment_status vào bảng orders
// Truy cập: http://localhost/project1/fix_orders_table.php
// Sau khi chạy xong, xóa file này đi.

require_once 'app/config/database.php';

$db = (new Database())->getConnection();

if (!$db) {
    die('Không kết nối được database.');
}

try {
    // Kiểm tra cột payment_method đã tồn tại chưa
    $stmt = $db->query("SHOW COLUMNS FROM orders LIKE 'payment_method'");
    if ($stmt->rowCount() == 0) {
        $db->exec("ALTER TABLE orders ADD COLUMN payment_method VARCHAR(20) DEFAULT 'cod'");
        echo "✅ Đã thêm cột payment_method<br>";
    } else {
        echo "⚠️ Cột payment_method đã tồn tại<br>";
    }

    // Kiểm tra cột payment_status đã tồn tại chưa
    $stmt = $db->query("SHOW COLUMNS FROM orders LIKE 'payment_status'");
    if ($stmt->rowCount() == 0) {
        $db->exec("ALTER TABLE orders ADD COLUMN payment_status VARCHAR(20) DEFAULT 'cod'");
        echo "✅ Đã thêm cột payment_status<br>";
    } else {
        echo "⚠️ Cột payment_status đã tồn tại<br>";
    }

    echo "<br><strong>🎉 Hoàn tất! Bây giờ bạn có thể thanh toán bình thường.</strong>";
    echo "<br><br><a href='Product'>← Quay lại trang sản phẩm</a>";
    echo "<br><br><em>Lưu ý: Hãy xóa file fix_orders_table.php sau khi chạy xong.</em>";

} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage();
}
