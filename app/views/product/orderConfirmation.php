<?php include 'app/views/shares/header.php'; ?>

<div class="main-card p-5 text-center" style="max-width: 700px; margin: 2rem auto;">
    <div class="mb-4">
        <div class="display-1 text-success mb-3">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <h1 class="fw-bold page-title">Đặt hàng thành công!</h1>
    </div>

    <p class="text-white-50 fs-5 mb-4">
        Cảm ơn bạn đã đặt hàng. Đơn hàng của bạn đã được xử lý thành công và sẽ được giao trong thời gian sớm nhất.
    </p>

    <div class="stat-card mb-4" style="display: inline-block; padding: 1.5rem 3rem;">
        <i class="bi bi-truck text-success" style="font-size: 2rem;"></i>
        <p class="text-white-50 mt-2 mb-0">Đơn hàng đang được chuẩn bị</p>
    </div>

    <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
        <a href="<?php echo url('Product'); ?>" class="btn btn-neon btn-lg rounded-pill px-5">
            <i class="bi bi-bag"></i> Tiếp tục mua sắm
        </a>
        <a href="<?php echo url(''); ?>" class="btn btn-outline-light btn-lg rounded-pill px-4">
            <i class="bi bi-house-door"></i> Về trang chủ
        </a>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
