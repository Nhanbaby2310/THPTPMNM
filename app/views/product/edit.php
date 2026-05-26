<?php include 'app/views/shares/header.php'; ?>

<!-- Page Header -->
<div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <h1>Cap nhat san pham</h1>
        <p>Chinh sua thong tin san pham #<?php echo $product->id; ?></p>
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

    <form method="POST" action="<?php echo url('Product/update'); ?>" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $product->id; ?>">

        <div class="form-group-modern">
            <label><i class="bi bi-type"></i> Ten san pham</label>
            <input type="text" class="form-input" name="name"
                   value="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>

        <div class="form-group-modern">
            <label><i class="bi bi-text-paragraph"></i> Mo ta</label>
            <textarea class="form-input" name="description" rows="4"
                      required><?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group-modern">
                <label><i class="bi bi-currency-dollar"></i> Gia ban (VND)</label>
                <input type="number" class="form-input" name="price" step="0.01"
                       value="<?php echo htmlspecialchars($product->price, ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>

            <div class="form-group-modern">
                <label><i class="bi bi-bookmark"></i> Danh muc</label>
                <select class="form-input" name="category_id" required>
                    <option value="">-- Chon danh muc --</option>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo $category->id; ?>"
                            <?php echo ($category->id == $product->category_id) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group-modern">
            <label><i class="bi bi-images"></i> Chon anh co san</label>
            <select class="form-input" name="existing_image">
                <option value="">-- Khong doi anh --</option>
                <?php if (!empty($availableImages)) : ?>
                    <?php foreach ($availableImages as $img) : ?>
                        <option value="<?php echo $img; ?>"
                            <?php echo ($product->image == $img) ? 'selected' : ''; ?>>
                            <?php echo basename($img); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="form-group-modern">
            <label><i class="bi bi-cloud-arrow-up"></i> Hoac tai anh moi</label>
            <input type="file" class="form-input" name="image" accept="image/*">
            <div class="form-hint">Neu chon anh moi, anh moi se duoc uu tien.</div>
        </div>

        <?php if (!empty($product->image)) : ?>
            <div class="form-group-modern">
                <label>Anh hien tai:</label>
                <div style="margin-top: 0.5rem;">
                    <img src="<?php echo url($product->image); ?>"
                         style="width: 120px; height: 90px; object-fit: cover; border-radius: var(--radius); border: 1px solid var(--border);"
                         alt="Anh san pham">
                </div>
            </div>
        <?php endif; ?>

        <div class="d-flex gap-3 mt-4">
            <button type="submit" class="btn-primary-custom">
                <i class="bi bi-check-lg"></i> Cap nhat
            </button>
            <a href="<?php echo url('Product'); ?>" class="btn-secondary-custom">
                Huy bo
            </a>
        </div>
    </form>
</div>

<?php include 'app/views/shares/footer.php'; ?>
