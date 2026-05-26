<?php include 'app/views/shares/header.php'; ?>

<!-- Page Header -->
<div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <h1>Them danh muc moi</h1>
        <p>Tao danh muc de phan loai san pham</p>
    </div>

    <a href="<?php echo url('Category'); ?>" class="btn-secondary-custom">
        <i class="bi bi-arrow-left"></i> Quay lai danh sach
    </a>
</div>

<!-- Form -->
<div class="form-modern" style="max-width: 700px;">
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

    <form method="POST" action="<?php echo url('Category/save'); ?>">

        <div class="form-group-modern">
            <label><i class="bi bi-type"></i> Ten danh muc</label>
            <input type="text" class="form-input" name="name"
                   placeholder="Vi du: Dien thoai, Laptop, Phu kien..." required>
            <div class="form-hint">Ten danh muc toi da 100 ky tu.</div>
        </div>

        <div class="form-group-modern">
            <label><i class="bi bi-text-paragraph"></i> Mo ta</label>
            <textarea class="form-input" name="description" rows="4"
                      placeholder="Nhap mo ta ngan ve danh muc nay"></textarea>
        </div>

        <div class="d-flex gap-3 mt-4">
            <button type="submit" class="btn-primary-custom">
                <i class="bi bi-check-lg"></i> Luu danh muc
            </button>
            <a href="<?php echo url('Category'); ?>" class="btn-secondary-custom">
                Huy bo
            </a>
        </div>
    </form>
</div>

<?php include 'app/views/shares/footer.php'; ?>
