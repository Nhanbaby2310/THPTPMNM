<?php include 'app/views/shares/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold page-title">Sửa danh mục</h1>
        <p class="text-white-50 mb-0">Cập nhật thông tin danh mục.</p>
    </div>

    <a href="<?php echo url('Category'); ?>" class="btn btn-outline-light rounded-pill px-4">
        <i class="bi bi-arrow-left"></i> Quay lại
    </a>
</div>

<div class="main-card p-4 p-lg-5">
    <?php if (!empty($errors)) : ?>
        <div class="alert alert-danger rounded-4">
            <strong>Có lỗi xảy ra:</strong>
            <ul class="mb-0 mt-2">
                <?php foreach ($errors as $error) : ?>
                    <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo url('Category/update'); ?>">
        <input type="hidden" name="id" value="<?php echo $category->id; ?>">

        <div class="mb-4">
            <label for="name" class="form-label fw-semibold">
                <i class="bi bi-tag"></i> Tên danh mục
            </label>
            <input type="text"
                   class="form-control form-control-lg rounded-4"
                   id="name"
                   name="name"
                   value="<?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>"
                   required>
        </div>

        <div class="mb-4">
            <label for="description" class="form-label fw-semibold">
                <i class="bi bi-card-text"></i> Mô tả
            </label>
            <textarea class="form-control rounded-4"
                      id="description"
                      name="description"
                      rows="5"><?php echo htmlspecialchars($category->description, ENT_QUOTES, 'UTF-8'); ?></textarea>
        </div>

        <div class="d-flex flex-wrap gap-3">
            <button type="submit" class="btn btn-neon btn-lg rounded-pill px-5">
                <i class="bi bi-check2-circle"></i> Cập nhật
            </button>

            <a href="<?php echo url('Category'); ?>" class="btn btn-outline-light btn-lg rounded-pill px-5">
                Hủy
            </a>
        </div>
    </form>
</div>

<?php include 'app/views/shares/footer.php'; ?>
