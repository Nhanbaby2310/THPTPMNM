<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COS340 - Bài 2</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="<?php echo url('public/assets/style.css'); ?>" rel="stylesheet">
</head>

<body class="home-hero">
    <div class="container py-5">
        <div class="row align-items-center justify-content-center min-vh-100">
            <div class="col-lg-10">
                <div class="hero-card p-5 text-dark">
                    <div class="row align-items-center">
                        <div class="col-lg-7">
                            <span class="badge rounded-pill px-4 py-2 mb-4" style="background: linear-gradient(135deg, #7c5cfc, #a78bfa); color: #fff;">
                                <i class="bi bi-database"></i> COS340 - Bài 2
                            </span>

                            <h1 class="display-4 fw-bold page-title mb-3">
                                Website bán hàng PHP + MySQL
                            </h1>

                            <p class="lead mb-4" style="color: #64748b;">
                                Hoàn thành CRUD sản phẩm, CRUD danh mục, kết nối MySQL bằng PDO, upload và hiển thị hình ảnh sản phẩm.
                            </p>

                            <div class="d-flex flex-wrap gap-3">
                                <a href="<?php echo url('Product'); ?>" class="btn btn-neon btn-lg rounded-pill px-5">
                                    <i class="bi bi-box-seam"></i> Quản lý sản phẩm
                                </a>

                                <a href="<?php echo url('Category'); ?>" class="btn btn-outline-light btn-lg rounded-pill px-5">
                                    <i class="bi bi-tags"></i> Quản lý danh mục
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-5 mt-5 mt-lg-0">
                            <div class="stat-card mb-3">
                                <h5 class="fw-bold"><i class="bi bi-check-circle text-success"></i> Product CRUD</h5>
                                <p class="mb-0">Thêm, xem, sửa, xóa sản phẩm.</p>
                            </div>

                            <div class="stat-card mb-3">
                                <h5 class="fw-bold"><i class="bi bi-check-circle text-success"></i> Category CRUD</h5>
                                <p class="mb-0">Thêm, sửa, xóa danh mục.</p>
                            </div>

                            <div class="stat-card">
                                <h5 class="fw-bold"><i class="bi bi-check-circle text-success"></i> Upload ảnh</h5>
                                <p class="mb-0">Lưu ảnh vào thư mục public/uploads.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <p class="text-center mt-4 mb-0" style="color: #94a3b8;">
                    Thực hành phát triển phần mềm mã nguồn mở - HUTECH
                </p>
            </div>
        </div>
    </div>
</body>
</html>
