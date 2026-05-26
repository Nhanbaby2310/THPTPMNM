<?php include 'app/views/shares/header.php'; ?>

<!-- Page Header -->
<div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <h1>Chi tiet san pham</h1>
        <p>Thong tin day du ve san pham #<?php echo htmlspecialchars($product->id, ENT_QUOTES, 'UTF-8'); ?></p>
    </div>

    <a href="<?php echo url('Product'); ?>" class="btn-secondary-custom">
        <i class="bi bi-arrow-left"></i> Quay lai danh sach
    </a>
</div>

<!-- Detail Grid -->
<div class="detail-grid">
    <!-- Image -->
    <div>
        <?php if (!empty($product->image)) : ?>
            <img src="<?php echo url($product->image); ?>" class="detail-image" alt="Anh san pham">
        <?php else : ?>
            <div class="detail-image-placeholder">
                <i class="bi bi-image"></i>
                <p style="font-size: 1rem; margin-top: 1rem;">Chua co hinh anh</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Info -->
    <div class="detail-info">
        <span class="product-category-badge" style="margin-bottom: 1rem; display: inline-block;">
            <?php echo htmlspecialchars($product->category_name ?? 'Chua phan loai', ENT_QUOTES, 'UTF-8'); ?>
        </span>

        <h2 class="detail-title">
            <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
        </h2>

        <p class="detail-desc">
            <?php echo nl2br(htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8')); ?>
        </p>

        <div class="detail-meta">
            <div class="detail-meta-item">
                <span>Gia ban</span>
                <strong style="color: var(--primary);">
                    <?php echo number_format($product->price, 0, ',', '.'); ?> VND
                </strong>
            </div>
            <div class="detail-meta-item">
                <span>Ma san pham</span>
                <strong>#<?php echo htmlspecialchars($product->id, ENT_QUOTES, 'UTF-8'); ?></strong>
            </div>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <a href="<?php echo url('Product/edit/' . $product->id); ?>" class="btn-primary-custom">
                <i class="bi bi-pencil"></i> Chinh sua
            </a>
            <a href="<?php echo url('Product/delete/' . $product->id); ?>"
               class="btn-secondary-custom" style="border-color: var(--danger); color: var(--danger);"
               onclick="return confirm('Ban co chac chan muon xoa san pham nay?');">
                <i class="bi bi-trash3"></i> Xoa san pham
            </a>
        </div>
    </div>
</div>

<!-- Gallery Section -->
<div class="card-modern" style="margin-top: 2rem;">
    <div class="card-header-modern">
        <div>
            <h5 style="font-weight: 600; margin: 0;">
                <i class="bi bi-images" style="color: var(--primary);"></i> Thu vien hinh anh
            </h5>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0.25rem 0 0;">
                Cac anh trong thu muc public/uploads
            </p>
        </div>
        <span style="background: #ecfdf5; color: #065f46; padding: 6px 14px; border-radius: var(--radius-full); font-size: 0.8rem; font-weight: 600;">
            <?php echo !empty($availableImages) ? count($availableImages) : 0; ?> anh
        </span>
    </div>

    <div class="card-body-modern">
        <?php if (empty($availableImages)) : ?>
            <div class="alert-modern info">
                <i class="bi bi-info-circle"></i> Chua co hinh anh nao trong thu muc uploads.
            </div>
        <?php else : ?>
            <div class="gallery-grid">
                <?php foreach ($availableImages as $img) : ?>
                    <div class="gallery-item">
                        <a href="<?php echo url($img); ?>" target="_blank">
                            <img src="<?php echo url($img); ?>" alt="Upload image">
                        </a>
                        <div class="gallery-item-info">
                            <?php echo htmlspecialchars(basename($img), ENT_QUOTES, 'UTF-8'); ?>
                            <?php if ($product->image == $img) : ?>
                                <span style="color: var(--success); font-weight: 600;"> (Dang dung)</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
