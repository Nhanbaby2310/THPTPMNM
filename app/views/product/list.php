<?php include 'app/views/shares/header.php'; ?>

<!-- Page Header -->
<div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <h1>San pham</h1>
        <p>Danh sach tat ca san pham trong he thong</p>
    </div>

    <a href="<?php echo url('Product/add'); ?>" class="btn-primary-custom">
        <i class="bi bi-plus-lg"></i> Them san pham moi
    </a>
</div>

<!-- Stats -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-box">
            <div class="stat-icon purple">
                <i class="bi bi-box-seam"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo count($products); ?></h3>
                <span>Tong san pham</span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-box">
            <div class="stat-icon orange">
                <i class="bi bi-database"></i>
            </div>
            <div class="stat-info">
                <h3>my_store</h3>
                <span>Co so du lieu</span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-box">
            <div class="stat-icon green">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-info">
                <h3>Active</h3>
                <span>Trang thai he thong</span>
            </div>
        </div>
    </div>
</div>

<!-- Product Grid -->
<?php if (empty($products)) : ?>
    <div class="card-modern">
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h3>Chua co san pham nao</h3>
            <p>Hay them san pham dau tien de bat dau quan ly cua hang.</p>
            <a href="<?php echo url('Product/add'); ?>" class="btn-primary-custom">
                <i class="bi bi-plus-circle"></i> Them san pham
            </a>
        </div>
    </div>
<?php else : ?>
    <div class="product-grid">
        <?php foreach ($products as $product) : ?>
            <div class="product-card">
                <?php if (!empty($product->image)) : ?>
                    <img src="<?php echo url($product->image); ?>" class="product-card-img" alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>">
                <?php else : ?>
                    <div class="product-card-img-placeholder">
                        <i class="bi bi-image"></i>
                    </div>
                <?php endif; ?>

                <div class="product-card-body">
                    <div class="product-card-title">
                        <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                    </div>

                    <div class="product-card-desc">
                        <?php echo htmlspecialchars(mb_strimwidth($product->description, 0, 80, '...'), ENT_QUOTES, 'UTF-8'); ?>
                    </div>

                    <div class="product-card-footer">
                        <span class="product-price">
                            <?php echo number_format($product->price, 0, ',', '.'); ?> VND
                        </span>
                        <span class="product-category-badge">
                            <?php echo htmlspecialchars($product->category_name ?? 'Chua phan loai', ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    </div>
                </div>

                <div class="product-actions">
                    <a href="<?php echo url('Product/show/' . $product->id); ?>" class="btn-icon view" title="Xem chi tiet">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="<?php echo url('Product/addToCart/' . $product->id); ?>" class="btn-icon" title="Them vao gio" style="color: var(--primary);">
                        <i class="bi bi-cart-plus"></i>
                    </a>
                    <a href="<?php echo url('Product/edit/' . $product->id); ?>" class="btn-icon edit" title="Chinh sua">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <a href="<?php echo url('Product/delete/' . $product->id); ?>" class="btn-icon delete" title="Xoa"
                       onclick="return confirm('Ban co chac chan muon xoa san pham nay?');">
                        <i class="bi bi-trash3"></i>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include 'app/views/shares/footer.php'; ?>
