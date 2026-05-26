<?php include 'app/views/shares/header.php'; ?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h1 class="fw-bold page-title">Giỏ hàng</h1>
        <p class="text-white-50 mb-0">Sản phẩm bạn đã chọn mua.</p>
    </div>

    <a href="<?php echo url('Product'); ?>" class="btn btn-outline-light rounded-pill px-4">
        <i class="bi bi-arrow-left"></i> Tiếp tục mua sắm
    </a>
</div>

<?php if (!empty($cart)): ?>
    <div class="main-card">
        <div class="p-4 border-bottom" style="border-color: var(--border-color) !important;">
            <h4 class="fw-bold mb-0">
                <i class="bi bi-cart3" style="color: var(--primary);"></i> Chi tiết giỏ hàng
            </h4>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($cart as $id => $item):
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    ?>
                        <tr>
                            <td>
                                <?php if (!empty($item['image'])): ?>
                                    <img src="<?php echo url($item['image']); ?>" class="product-img" alt="Ảnh SP">
                                <?php else: ?>
                                    <div class="empty-img">
                                        <i class="bi bi-image"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold" style="color: var(--primary);">
                                    <?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                            </td>
                            <td>
                                <span class="badge rounded-pill bg-success px-3 py-2">
                                    <?php echo number_format($item['price'], 0, ',', '.'); ?> VNĐ
                                </span>
                            </td>
                            <td>
                                <form method="POST" action="<?php echo url('Product/updateCart'); ?>" class="d-flex align-items-center gap-2">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>"
                                           min="1" max="99" class="form-control form-control-sm rounded-3" style="width: 70px;">
                                    <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill">
                                        <i class="bi bi-arrow-clockwise"></i>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <strong style="color: var(--primary);">
                                    <?php echo number_format($subtotal, 0, ',', '.'); ?> VNĐ
                                </strong>
                            </td>
                            <td class="text-center">
                                <a href="<?php echo url('Product/removeFromCart/' . $id); ?>"
                                   class="btn btn-danger btn-sm rounded-pill px-3"
                                   onclick="return confirm('Bạn muốn xóa sản phẩm này khỏi giỏ hàng?');">
                                    <i class="bi bi-trash"></i> Xóa
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end fw-bold" style="font-size: 1.1rem;">
                            Tổng cộng:
                        </td>
                        <td colspan="2">
                            <span class="badge bg-danger px-4 py-2" style="font-size: 1.1rem;">
                                <?php echo number_format($total, 0, ',', '.'); ?> VNĐ
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="mt-4 d-flex flex-wrap gap-3">
        <a href="<?php echo url('Product/checkout'); ?>" class="btn btn-neon btn-lg rounded-pill px-5">
            <i class="bi bi-credit-card"></i> Tiến hành thanh toán
        </a>
        <a href="<?php echo url('Product'); ?>" class="btn btn-outline-light btn-lg rounded-pill px-4">
            <i class="bi bi-bag"></i> Tiếp tục mua sắm
        </a>
    </div>

<?php else: ?>
    <div class="main-card p-5 text-center">
        <div class="display-1 mb-3" style="color: var(--primary-light);">
            <i class="bi bi-cart-x"></i>
        </div>
        <h3 class="fw-bold">Giỏ hàng trống</h3>
        <p class="text-white-50">Bạn chưa thêm sản phẩm nào vào giỏ hàng.</p>
        <a href="<?php echo url('Product'); ?>" class="btn btn-neon rounded-pill px-5">
            <i class="bi bi-bag-plus"></i> Mua sắm ngay
        </a>
    </div>
<?php endif; ?>

<?php include 'app/views/shares/footer.php'; ?>
