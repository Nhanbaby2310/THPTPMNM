<?php include 'app/views/shares/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold page-title">Thêm sản phẩm</h1>
        <p class="text-white-50 mb-0">Nhập thông tin sản phẩm và chọn danh mục.</p>
    </div>

    <a href="<?php echo url('Product'); ?>" class="btn btn-outline-light rounded-pill px-4">
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

    <form method="POST" action="<?php echo url('Product/save'); ?>" enctype="multipart/form-data">
        <div class="mb-4">
            <label for="name" class="form-label fw-semibold">
                <i class="bi bi-tag"></i> Tên sản phẩm
            </label>

            <input type="text"
                   class="form-control form-control-lg rounded-4"
                   id="name"
                   name="name"
                   placeholder="Ví dụ: Laptop Hutech 2024"
                   required>

            <div class="form-text text-white-50">
                Tên sản phẩm từ 10 đến 100 ký tự.
            </div>
        </div>

        <div class="mb-4">
            <label for="description" class="form-label fw-semibold">
                <i class="bi bi-card-text"></i> Mô tả
            </label>

            <textarea class="form-control rounded-4"
                      id="description"
                      name="description"
                      rows="5"
                      placeholder="Nhập mô tả sản phẩm"
                      required></textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <label for="price" class="form-label fw-semibold">
                    <i class="bi bi-cash-stack"></i> Giá
                </label>

                <div class="input-group input-group-lg">
                    <input type="number"
                           class="form-control rounded-start-4"
                           id="price"
                           name="price"
                           step="0.01"
                           placeholder="Ví dụ: 12300"
                           required>
                    <span class="input-group-text rounded-end-4">VNĐ</span>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <label for="category_id" class="form-label fw-semibold">
                    <i class="bi bi-tags"></i> Danh mục
                </label>

                <select class="form-select form-select-lg rounded-4" id="category_id" name="category_id" required>
                    <option value="">-- Chọn danh mục --</option>

                    <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo $category->id; ?>">
                            <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="mb-4">
            <label for="existing_image" class="form-label fw-semibold">
                <i class="bi bi-images"></i> Chọn ảnh có sẵn trong thư mục uploads
            </label>

            <select class="form-select form-select-lg rounded-4" id="existing_image" name="existing_image">
                <option value="">-- Chưa chọn ảnh có sẵn --</option>

                <?php if (!empty($availableImages)) : ?>
                    <?php foreach ($availableImages as $img) : ?>
                        <option value="<?php echo $img; ?>">
                            <?php echo basename($img); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>

            <div class="form-text text-white-50">
                Danh sách này lấy ảnh từ thư mục public/uploads.
            </div>
        </div>

        <div class="mb-4">
            <label for="image" class="form-label fw-semibold">
                <i class="bi bi-upload"></i> Hoặc tải ảnh mới từ máy
            </label>

            <input type="file"
                   class="form-control form-control-lg rounded-4"
                   id="image"
                   name="image"
                   accept="image/*">

            <div class="form-text text-white-50">
                Hỗ trợ JPG, PNG, GIF, WEBP. Tối đa 5MB. Nếu chọn ảnh mới, ảnh mới sẽ được ưu tiên.
            </div>
        </div>

        <div class="d-flex flex-wrap gap-3">
            <button type="submit" class="btn btn-neon btn-lg rounded-pill px-5">
                <i class="bi bi-save"></i> Lưu sản phẩm
            </button>

            <a href="<?php echo url('Product'); ?>" class="btn btn-outline-light btn-lg rounded-pill px-5">
                Hủy
            </a>
        </div>
    </form>
</div>

<?php include 'app/views/shares/footer.php'; ?>