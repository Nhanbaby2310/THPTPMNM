<?php include 'app/views/shares/header.php'; ?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h1 class="fw-bold page-title">Danh sách danh mục</h1>
        <p class="text-white-50 mb-0">Quản lý bảng category trong MySQL.</p>
    </div>

    <a href="<?php echo url('Category/add'); ?>" class="btn btn-neon rounded-pill px-4">
        <i class="bi bi-plus-lg"></i> Thêm danh mục
    </a>
</div>

<div class="main-card">
    <div class="p-4 border-bottom" style="border-color: var(--border-color) !important;">
        <h4 class="fw-bold mb-0">
            <i class="bi bi-tags" style="color: var(--primary);"></i> Bảng danh mục
        </h4>
    </div>

    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên danh mục</th>
                    <th>Mô tả</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($categories as $category) : ?>
                    <tr>
                        <td>
                            <span class="badge bg-secondary rounded-pill">
                                #<?php echo htmlspecialchars($category->id, ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </td>

                        <td class="fw-bold" style="color: var(--primary);">
                            <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                        </td>

                        <td class="text-white-50">
                            <?php echo htmlspecialchars($category->description, ENT_QUOTES, 'UTF-8'); ?>
                        </td>

                        <td class="text-center">
                            <a href="<?php echo url('Category/edit/' . $category->id); ?>"
                               class="btn btn-warning btn-sm rounded-pill px-3">
                                <i class="bi bi-pencil-square"></i> Sửa
                            </a>

                            <a href="<?php echo url('Category/delete/' . $category->id); ?>"
                               class="btn btn-danger btn-sm rounded-pill px-3"
                               onclick="return confirm('Xóa danh mục có thể xóa các sản phẩm thuộc danh mục này. Bạn có chắc chắn không?');">
                                <i class="bi bi-trash"></i> Xóa
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
