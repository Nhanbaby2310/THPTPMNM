<?php include 'app/views/shares/header.php'; ?>

<!-- Page Header -->
<div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <h1>Thanh toan</h1>
        <p>Nhap thong tin giao hang de hoan tat don hang</p>
    </div>
    <a href="<?php echo url('Product/cart'); ?>" class="btn-secondary-custom">
        <i class="bi bi-arrow-left"></i> Quay lai gio hang
    </a>
</div>

<div class="row g-4">
    <!-- Form thanh toan -->
    <div class="col-lg-7">
        <div class="form-modern">
            <h4 style="font-weight: 600; margin-bottom: 1.5rem;">
                <i class="bi bi-person-lines-fill" style="color: var(--primary);"></i> Thong tin nhan hang
            </h4>

            <form method="POST" action="<?php echo url('Product/processCheckout'); ?>">
                <div class="form-group-modern">
                    <label><i class="bi bi-person"></i> Ho va ten</label>
                    <input type="text" class="form-input" name="name" placeholder="Nhap ho ten nguoi nhan" required>
                </div>

                <div class="form-group-modern">
                    <label><i class="bi bi-telephone"></i> So dien thoai</label>
                    <input type="text" class="form-input" name="phone" placeholder="Nhap so dien thoai" required>
                </div>

                <div class="form-group-modern">
                    <label><i class="bi bi-geo-alt"></i> Dia chi giao hang</label>
                    <textarea class="form-input" name="address" rows="3" placeholder="Nhap dia chi giao hang chi tiet" required></textarea>
                </div>

                <!-- Phuong thuc thanh toan -->
                <div class="form-group-modern">
                    <label><i class="bi bi-credit-card"></i> Phuong thuc thanh toan</label>
                    <div style="display: flex; flex-direction: column; gap: 12px; margin-top: 8px;">
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="cod" checked>
                            <div class="payment-option-content">
                                <i class="bi bi-truck" style="font-size: 1.3rem; color: var(--success);"></i>
                                <div>
                                    <strong>Thanh toan khi nhan hang (COD)</strong>
                                    <p style="margin:0; font-size: 0.8rem; color: var(--text-muted);">Tra tien mat khi nhan duoc hang</p>
                                </div>
                            </div>
                        </label>
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="vnpay">
                            <div class="payment-option-content">
                                <i class="bi bi-bank" style="font-size: 1.3rem; color: var(--primary);"></i>
                                <div>
                                    <strong>Thanh toan qua VNPay</strong>
                                    <p style="margin:0; font-size: 0.8rem; color: var(--text-muted);">ATM / Visa / MasterCard / QR Code</p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button type="submit" class="btn-primary-custom">
                        <i class="bi bi-check-circle"></i> Xac nhan dat hang
                    </button>
                    <a href="<?php echo url('Product/cart'); ?>" class="btn-secondary-custom">
                        Quay lai
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tom tat don hang -->
    <div class="col-lg-5">
        <div class="card-modern">
            <div class="card-header-modern">
                <h5 style="font-weight: 600; margin: 0;">
                    <i class="bi bi-receipt" style="color: var(--primary);"></i> Don hang cua ban
                </h5>
            </div>
            <div class="card-body-modern">
                <?php if (!empty($cart)):
                    $total = 0;
                ?>
                    <?php foreach ($cart as $id => $item):
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    ?>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid var(--border);">
                            <div>
                                <strong style="font-size: 0.9rem;"><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                <div style="font-size: 0.8rem; color: var(--text-muted);">
                                    <?php echo number_format($item['price'], 0, ',', '.'); ?> x <?php echo $item['quantity']; ?>
                                </div>
                            </div>
                            <strong><?php echo number_format($subtotal, 0, ',', '.'); ?> VND</strong>
                        </div>
                    <?php endforeach; ?>

                    <div style="margin-top: 1.5rem; padding: 1rem; border-radius: var(--radius); background: linear-gradient(135deg, #eef2ff, #fdf2f8); display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-weight: 600;">Tong thanh toan:</span>
                        <span style="font-weight: 700; font-size: 1.2rem; color: var(--primary);">
                            <?php echo number_format($total, 0, ',', '.'); ?> VND
                        </span>
                    </div>
                <?php else: ?>
                    <p style="color: var(--text-muted);">Gio hang trong.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
