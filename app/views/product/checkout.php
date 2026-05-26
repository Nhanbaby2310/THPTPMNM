<?php include 'app/views/shares/header.php'; ?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h1 class="fw-bold page-title">Thanh toán</h1>
        <p class="text-white-50 mb-0">Nhập thông tin giao hàng để hoàn tất đơn hàng.</p>
    </div>

    <a href="<?php echo url('Product/cart'); ?>" class="btn btn-outline-light rounded-pill px-4">
        <i class="bi bi-arrow-left"></i> Quay lại giỏ hàng
    </a>
</div>

<div class="row g-4">
    <!-- Form thanh toán -->
    <div class="col-lg-7">
        <div class="main-card p-4 p-lg-5">
            <h4 class="fw-bold mb-4">
                <i class="bi bi-person-lines-fill" style="color: var(--primary);"></i> Thông tin nhận hàng
            </h4>

            <form method="POST" action="<?php echo url('Product/processCheckout'); ?>">
                <div class="mb-4">
                    <label for="name" class="form-label fw-semibold">
                        <i class="bi bi-person"></i> Họ và tên
                    </label>
                    <input type="text" class="form-control form-control-lg rounded-4"
                           id="name" name="name" placeholder="Nhập họ tên người nhận" required>
                </div>

                <div class="mb-4">
                    <label for="phone" class="form-label fw-semibold">
                        <i class="bi bi-telephone"></i> Số điện thoại
                    </label>
                    <input type="text" class="form-control form-control-lg rounded-4"
                           id="phone" name="phone" placeholder="Nhập số điện thoại" required>
                </div>

                <div class="mb-4">
                    <label for="address" class="form-label fw-semibold">
                        <i class="bi bi-geo-alt"></i> Địa chỉ giao hàng
                    </label>
                    <textarea class="form-control rounded-4" id="address" name="address"
                              rows="4" placeholder="Nhập địa chỉ giao hàng chi tiết" required></textarea>
                </div>

                <div class="d-flex flex-wrap gap-3">
                    <button type="submit" class="btn btn-neon btn-lg rounded-pill px-5">
                        <i class="bi bi-check-circle"></i> Xác nhận đặt hàng
                    </button>
                    <a href="<?php echo url('Product/cart'); ?>" class="btn btn-outline-light btn-lg rounded-pill px-4">
                        Quay lại
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tóm tắt đơn hàng -->
    <div class="col-lg-5">
        <div class="main-card p-4">
            <h4 class="fw-bold mb-4">
                <i class="bi bi-receipt" style="color: var(--primary);"></i> Đơn hàng của bạn
            </h4>

            <?php if (!empty($cart)):
                $total = 0;
            ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($cart as $id => $item):
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center"
                             style="background: transparent; border-color: var(--border-color);">
                            <div>
                                <div class="fw-bold" style="color: var(--primary);">
                                    <?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                                <small class="text-white-50">
                                    <?php echo number_format($item['price'], 0, ',', '.'); ?> x <?php echo $item['quantity']; ?>
                                </small>
                            </div>
                            <span class="fw-bold">
                                <?php echo number_format($subtotal, 0, ',', '.'); ?> VNĐ
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="mt-4 p-3 rounded-4" style="background: linear-gradient(135deg, #ede9fe, #fce7f3);">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold" style="font-size: 1.1rem;">Tổng thanh toán:</span>
                        <span class="fw-bold" style="font-size: 1.3rem; color: var(--primary);">
                            <?php echo number_format($total, 0, ',', '.'); ?> VNĐ
                        </span>
                    </div>
                </div>
            <?php else: ?>
                <p class="text-white-50">Giỏ hàng trống.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
