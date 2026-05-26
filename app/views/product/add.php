<?php include 'app/views/shares/header.php'; ?>

<!-- Page Header -->
<div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <h1>Them san pham moi</h1>
        <p>Dien thong tin san pham va chon danh muc phu hop</p>
    </div>

    <a href="<?php echo url('Product'); ?>" class="btn-secondary-custom">
        <i class="bi bi-arrow-left"></i> Quay lai danh sach
    </a>
</div>

<!-- Form -->
<div class="form-modern">
    <?php if (!empty($errors)) : ?>
        <div class="alert-modern error">
            <strong><i class="bi bi-exclamation-triangle"></i> Co loi xay ra:</strong>
            <ul style="margin: 0.5rem 0 0 1.2rem; padding: 0;">
                <?php foreach ($errors as $error) : ?>
                    <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo url('Product/save'); ?>" enctype="multipart/form-data">

        <div class="form-group-modern">
            <label><i class="bi bi-type"></i> Ten san pham</label>
            <input type="text" class="form-input" name="name"
                   placeholder="Nhap ten san pham (10-100 ky tu)" required>
            <div class="form-hint">Ten san pham tu 10 den 100 ky tu.</div>
        </div>

        <div class="form-group-modern">
            <label><i class="bi bi-text-paragraph"></i> Mo ta</label>
            <textarea class="form-input" name="description" rows="4"
                      placeholder="Nhap mo ta chi tiet ve san pham" required></textarea>
        </div>

        <div class="form-row">
            <div class="form-group-modern">
                <label><i class="bi bi-currency-dollar"></i> Gia ban (VND)</label>
                <input type="number" class="form-input" name="price"
                       step="0.01" placeholder="Vi du: 15000000" required>
            </div>

            <div class="form-group-modern">
                <label><i class="bi bi-bookmark"></i> Danh muc</label>
                <select class="form-input" name="category_id" required>
                    <option value="">-- Chon danh muc --</option>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo $category->id; ?>">
                            <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group-modern">
            <label><i class="bi bi-images"></i> Chon anh co san</label>
            <select class="form-input" name="existing_image">
                <option value="">-- Khong chon anh co san --</option>
                <?php if (!empty($availableImages)) : ?>
                    <?php foreach ($availableImages as $img) : ?>
                        <option value="<?php echo $img; ?>">
                            <?php echo basename($img); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <div class="form-hint">Chon tu danh sach anh trong thu muc public/uploads.</div>
        </div>

        <div class="form-group-modern">
            <label><i class="bi bi-cloud-arrow-up"></i> Hoac tai anh moi</label>
            <input type="file" class="form-input" name="image" accept="image/*">
            <div class="form-hint">Ho tro JPG, PNG, GIF, WEBP. Toi da 5MB. Anh moi se duoc uu tien.</div>
        </div>

        <div class="d-flex gap-3 mt-4">
            <button type="submit" class="btn-primary-custom">
                <i class="bi bi-check-lg"></i> Luu san pham
            </button>
            <a href="<?php echo url('Product'); ?>" class="btn-secondary-custom">
                Huy bo
            </a>
        </div>
    </form>
</div>

<?php include 'app/views/shares/footer.php'; ?>
