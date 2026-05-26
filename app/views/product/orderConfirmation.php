<?php include 'app/views/shares/header.php'; ?>

<div style="max-width: 600px; margin: 3rem auto; text-align: center;">
    <div class="card-modern" style="padding: 3rem;">
        <div style="font-size: 4rem; color: var(--success); margin-bottom: 1rem;">
            <i class="bi bi-check-circle-fill"></i>
        </div>

        <h1 style="font-weight: 700; margin-bottom: 0.5rem;">Dat hang thanh cong!</h1>

        <p style="color: var(--text-secondary); font-size: 1.05rem; margin-bottom: 2rem;">
            Cam on ban da dat hang. Don hang cua ban da duoc xu ly thanh cong va se duoc giao trong thoi gian som nhat.
        </p>

        <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: var(--radius); padding: 1rem; margin-bottom: 2rem;">
            <i class="bi bi-truck" style="font-size: 1.5rem; color: var(--success);"></i>
            <p style="margin: 0.5rem 0 0; color: #065f46; font-weight: 500;">Don hang dang duoc chuan bi giao</p>
        </div>

        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="<?php echo url('Product'); ?>" class="btn-primary-custom">
                <i class="bi bi-bag"></i> Tiep tuc mua sam
            </a>
            <a href="<?php echo url(''); ?>" class="btn-secondary-custom">
                <i class="bi bi-grid-1x2"></i> Ve Dashboard
            </a>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
