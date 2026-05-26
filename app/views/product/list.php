<?php include 'app/views/shares/header.php'; ?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h1 class="fw-bold page-title">Danh sách sản phẩm</h1>
        <p class="text-white-50 mb-0">Dữ liệu được lấy từ bảng product và category trong MySQL.</p>
    </div>

    <a href="<?php echo url('Product/add'); ?>" class="btn btn-neon rounded-pill px-4">
        <i class="bi bi-plus-lg"></i> Thêm sản phẩm
    </a>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <p class="text-white-50 mb-1">Tổng sản phẩm</p>
            <h2 class="fw-bold mb-0"><?php echo count($products); ?></h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card">
            <p class="text-white-50 mb-1">Database</p>
            <h2 class="fw-bold mb-0">my_store</h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card">
            <p class="text-white-50 mb-1">Bài thực hành</p>
            <h2 class="fw-bold mb-0">Bài 2</h2>
        </div>
    </div>
</div>

<?php if (empty($products)) : ?>
    <div class="main-card p-5 text-center">
        <div class="display-1 text-info mb-3">
            <i class="bi bi-inbox"></i>
        </div>

        <h3 class="fw-bold">Chưa có sản phẩm</h3>
        <p class="text-white-50">Hãy thêm sản phẩm mới để kiểm tra chức năng CRUD.</p>

        <a href="<?php echo url('Product/add'); ?>" class="btn btn-neon rounded-pill px-5">
            <i class="bi bi-plus-circle"></i> Thêm sản phẩm mới
        </a>
    </div>
<?php else : ?>
    <div class="main-card">
        <div class="p-4 border-bottom border-secondary border-opacity-25">
            <h4 class="fw-bold mb-0">
                <i class="bi bi-list-check text-info"></i> Bảng sản phẩm
            </h4>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($products as $product) : ?>
                        <tr>
                            <td>
                                <span class="badge bg-secondary rounded-pill">
                                    #<?php echo htmlspecialchars($product->id, ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </td>

                            <td>
                                <?php if (!empty($product->image)) : ?>
                                    <img src="<?php echo url($product->image); ?>" class="product-img" alt="Ảnh sản phẩm">
                                <?php else : ?>
                                    <div class="empty-img">
                                        <i class="bi bi-image"></i>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <td>
                                <div class="fw-bold text-info">
                                    <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                                <small class="text-white-50">
                                    <?php echo htmlspecialchars(mb_strimwidth($product->description, 0, 60, '...'), ENT_QUOTES, 'UTF-8'); ?>
                                </small>
                            </td>

                            <td>
                                <span class="badge rounded-pill bg-primary">
                                    <?php echo htmlspecialchars($product->category_name ?? 'Chưa có', ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </td>

                            <td>
                                <span class="badge rounded-pill bg-success px-3 py-2">
                                    <?php echo number_format($product->price, 0, ',', '.'); ?> VNĐ
                                </span>
                            </td>

                            <td class="text-center">
                                <a href="<?php echo url('Product/show/' . $product->id); ?>"
                                   class="btn btn-info btn-sm rounded-pill px-3">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a href="<?php echo url('Product/edit/' . $product->id); ?>"
                                   class="btn btn-warning btn-sm rounded-pill px-3">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <a href="<?php echo url('Product/delete/' . $product->id); ?>"
                                   class="btn btn-danger btn-sm rounded-pill px-3"
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php include 'app/views/shares/footer.php'; ?>
