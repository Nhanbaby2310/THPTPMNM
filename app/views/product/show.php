<?php include 'app/views/shares/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold page-title">Chi tiết sản phẩm</h1>
        <p class="text-white-50 mb-0">
            Xem thông tin sản phẩm, hình ảnh chính và danh sách ảnh trong thư mục uploads.
        </p>
    </div>

    <a href="<?php echo url('Product'); ?>" class="btn btn-outline-light rounded-pill px-4">
        <i class="bi bi-arrow-left"></i> Quay lại
    </a>
</div>

<div class="main-card p-4 p-lg-5 mb-4">
    <div class="row g-4 align-items-start">

        <div class="col-md-5">
            <?php if (!empty($product->image)) : ?>
                <img src="<?php echo url($product->image); ?>"
                     class="img-fluid rounded-4 shadow"
                     style="width: 100%; max-height: 420px; object-fit: cover; border: 2px solid var(--border-color);"
                     alt="Ảnh sản phẩm">
            <?php else : ?>
                <div class="p-5 text-center rounded-4"
                     style="background: linear-gradient(135deg, #ede9fe, #fce7f3); min-height: 300px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                    <i class="bi bi-image display-1" style="color: var(--primary-light);"></i>
                    <p class="mt-3 mb-0" style="color: var(--text-muted);">
                        Sản phẩm này chưa có hình ảnh
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-md-7">
            <span class="badge bg-info rounded-pill px-3 py-2 mb-3">
                Mã sản phẩm: #<?php echo htmlspecialchars($product->id, ENT_QUOTES, 'UTF-8'); ?>
            </span>

            <h2 class="fw-bold mb-3" style="color: var(--primary);">
                <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
            </h2>

            <p class="fs-5" style="color: var(--text-muted);">
                <?php echo nl2br(htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8')); ?>
            </p>

            <div class="row mt-4">
                <div class="col-md-6 mb-3">
                    <div class="stat-card">
                        <p class="mb-1">Danh mục</p>

                        <h5 class="mb-0">
                            <span class="badge bg-primary rounded-pill px-3 py-2">
                                <?php echo htmlspecialchars($product->category_name ?? 'Chưa có', ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </h5>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="stat-card">
                        <p class="mb-1">Giá sản phẩm</p>

                        <h4 class="text-success fw-bold mb-0">
                            <?php echo number_format($product->price, 0, ',', '.'); ?> VNĐ
                        </h4>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex flex-wrap gap-2">
                <a href="<?php echo url('Product/edit/' . $product->id); ?>"
                   class="btn btn-warning rounded-pill px-4">
                    <i class="bi bi-pencil-square"></i> Sửa sản phẩm
                </a>

                <a href="<?php echo url('Product/delete/' . $product->id); ?>"
                   class="btn btn-danger rounded-pill px-4"
                   onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                    <i class="bi bi-trash"></i> Xóa sản phẩm
                </a>
            </div>
        </div>

    </div>
</div>

<div class="main-card p-4 p-lg-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1" style="color: var(--primary);">
                <i class="bi bi-images"></i> Danh sách hình ảnh
            </h3>

            <p class="text-white-50 mb-0">
                Các ảnh này được lấy từ thư mục <strong>public/uploads</strong>.
            </p>
        </div>

        <span class="badge bg-success rounded-pill px-3 py-2">
            <?php echo !empty($availableImages) ? count($availableImages) : 0; ?> ảnh
        </span>
    </div>

    <?php if (empty($availableImages)) : ?>
        <div class="alert alert-info rounded-4">
            Chưa có hình ảnh nào trong thư mục <strong>public/uploads</strong>.
            Bạn hãy thêm hoặc sửa sản phẩm rồi upload ảnh trước.
        </div>
    <?php else : ?>
        <div class="row g-4">
            <?php foreach ($availableImages as $img) : ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 rounded-4 overflow-hidden">

                        <a href="<?php echo url($img); ?>" target="_blank">
                            <img src="<?php echo url($img); ?>"
                                 class="card-img-top"
                                 style="height: 180px; object-fit: cover;"
                                 alt="Ảnh trong uploads">
                        </a>

                        <div class="card-body">
                            <p class="card-text small mb-2">
                                <?php echo htmlspecialchars(basename($img), ENT_QUOTES, 'UTF-8'); ?>
                            </p>

                            <?php if ($product->image == $img) : ?>
                                <span class="badge bg-success rounded-pill">
                                    Ảnh đang dùng
                                </span>
                            <?php else : ?>
                                <span class="badge bg-secondary rounded-pill">
                                    Ảnh có sẵn
                                </span>
                            <?php endif; ?>

                            <div class="mt-3">
                                <a href="<?php echo url($img); ?>"
                                   target="_blank"
                                   class="btn btn-outline-light btn-sm rounded-pill w-100">
                                    <i class="bi bi-eye"></i> Xem ảnh
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'app/views/shares/footer.php'; ?>
