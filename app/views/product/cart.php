<?php include 'app/views/shares/header.php'; ?>

<!-- Page Header -->
<div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <h1>Gio hang cua ban</h1>
        <p>San pham ban da chon mua</p>
    </div>
    <a href="<?php echo url('Product'); ?>" class="btn-secondary-custom">
        <i class="bi bi-arrow-left"></i> Tiep tuc mua sam
    </a>
</div>

<?php if (!empty($cart)): ?>
    <div class="card-modern">
        <div class="card-header-modern">
            <h5 style="font-weight: 600; margin: 0;">
                <i class="bi bi-cart3" style="color: var(--primary);"></i> Chi tiet gio hang
            </h5>
            <span style="background: #eef2ff; color: var(--primary); padding: 6px 14px; border-radius: var(--radius-full); font-size: 0.8rem; font-weight: 600;">
                <?php echo count($cart); ?> san pham
            </span>
        </div>

        <div style="overflow-x: auto;">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Hinh anh</th>
                        <th>Ten san pham</th>
                        <th>Gia</th>
                        <th>So luong</th>
                        <th>Thanh tien</th>
                        <th style="text-align:center;">Thao tac</th>
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
                                    <img src="<?php echo url($item['image']); ?>" style="width:70px; height:55px; object-fit:cover; border-radius: 10px; border: 1px solid var(--border);" alt="">
                                <?php else: ?>
                                    <div style="width:70px; height:55px; border-radius:10px; background: linear-gradient(135deg, #eef2ff, #fdf2f8); display:flex; align-items:center; justify-content:center; color:var(--text-muted);"><i class="bi bi-image"></i></div>
                                <?php endif; ?>
                            </td>
                            <td><strong><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
                            <td><?php echo number_format($item['price'], 0, ',', '.'); ?> VND</td>
                            <td>
                                <form method="POST" action="<?php echo url('Product/updateCart'); ?>" style="display:flex; align-items:center; gap:6px;">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="99" class="form-input" style="width:65px; padding:8px 10px; font-size:0.85rem;">
                                    <button type="submit" class="btn-icon" title="Cap nhat"><i class="bi bi-arrow-clockwise"></i></button>
                                </form>
                            </td>
                            <td><strong style="color: var(--primary);"><?php echo number_format($subtotal, 0, ',', '.'); ?> VND</strong></td>
                            <td style="text-align:center;">
                                <a href="<?php echo url('Product/removeFromCart/' . $id); ?>" class="btn-icon delete" onclick="return confirm('Xoa san pham nay khoi gio hang?');">
                                    <i class="bi bi-trash3"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div style="padding: 1.5rem 2rem; border-top: 1px solid var(--border); display:flex; justify-content:flex-end; align-items:center; gap: 1rem;">
            <span style="font-size: 1.1rem; font-weight: 600;">Tong cong:</span>
            <span style="font-size: 1.3rem; font-weight: 700; color: var(--primary); background: #eef2ff; padding: 8px 20px; border-radius: var(--radius-full);">
                <?php echo number_format($total, 0, ',', '.'); ?> VND
            </span>
        </div>
    </div>

    <div style="margin-top: 1.5rem; display: flex; gap: 1rem; flex-wrap: wrap;">
        <a href="<?php echo url('Product/checkout'); ?>" class="btn-primary-custom">
            <i class="bi bi-credit-card"></i> Tien hanh thanh toan
        </a>
        <a href="<?php echo url('Product'); ?>" class="btn-secondary-custom">
            <i class="bi bi-bag"></i> Tiep tuc mua sam
        </a>
    </div>

<?php else: ?>
    <div class="card-modern">
        <div class="empty-state">
            <i class="bi bi-cart-x"></i>
            <h3>Gio hang trong</h3>
            <p>Ban chua them san pham nao vao gio hang.</p>
            <a href="<?php echo url('Product'); ?>" class="btn-primary-custom">
                <i class="bi bi-bag-plus"></i> Mua sam ngay
            </a>
        </div>
    </div>
<?php endif; ?>

<?php include 'app/views/shares/footer.php'; ?>
