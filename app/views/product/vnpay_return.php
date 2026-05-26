<?php include 'app/views/shares/header.php'; ?>

<div style="max-width: 600px; margin: 3rem auto; text-align: center;">
    <div class="card-modern" style="padding: 3rem;">

        <?php if ($payment_success): ?>
            <div style="font-size: 4rem; color: var(--success); margin-bottom: 1rem;">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <h1 style="font-weight: 700; margin-bottom: 0.5rem; color: var(--success);">Thanh toan thanh cong!</h1>
            <p style="color: var(--text-secondary); font-size: 1.05rem; margin-bottom: 2rem;">
                <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
            </p>

            <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: var(--radius); padding: 1rem; margin-bottom: 2rem;">
                <i class="bi bi-shield-check" style="font-size: 1.5rem; color: var(--success);"></i>
                <p style="margin: 0.5rem 0 0; color: #065f46; font-weight: 500;">Giao dich da duoc xac nhan boi VNPay</p>
            </div>
        <?php else: ?>
            <div style="font-size: 4rem; color: var(--danger); margin-bottom: 1rem;">
                <i class="bi bi-x-circle-fill"></i>
            </div>
            <h1 style="font-weight: 700; margin-bottom: 0.5rem; color: var(--danger);">Thanh toan that bai!</h1>
            <p style="color: var(--text-secondary); font-size: 1.05rem; margin-bottom: 2rem;">
                <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
            </p>

            <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: var(--radius); padding: 1rem; margin-bottom: 2rem;">
                <i class="bi bi-exclamation-triangle" style="font-size: 1.5rem; color: var(--danger);"></i>
                <p style="margin: 0.5rem 0 0; color: #991b1b; font-weight: 500;">Vui long thu lai hoac chon phuong thuc thanh toan khac</p>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="<?php echo url('Product'); ?>" class="btn-primary-custom">
                <i class="bi bi-bag"></i> Tiep tuc mua sam
            </a>
            <a href="<?php echo url(''); ?>" class="btn-secondary-custom">
                <i class="bi bi-grid-1x2"></i> Ve trang chu
            </a>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
