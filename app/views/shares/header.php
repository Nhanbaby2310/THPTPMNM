<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COS340 - Website bán hàng</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="<?php echo url('public/assets/style.css'); ?>" rel="stylesheet">
</head>

<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 sidebar">
            <div class="mb-4">
                <h3 class="brand mb-1">
                    <i class="bi bi-shop-window"></i> COS340
                </h3>
                <p class="brand-sub mb-0">PHP MVC + MySQL</p>
            </div>

            <nav>
                <a href="<?php echo url(''); ?>" class="nav-btn">
                    <i class="bi bi-house-door"></i> Trang chủ
                </a>

                <a href="<?php echo url('Product'); ?>" class="nav-btn">
                    <i class="bi bi-box-seam"></i> Sản phẩm
                </a>

                <a href="<?php echo url('Product/add'); ?>" class="nav-btn">
                    <i class="bi bi-plus-circle"></i> Thêm sản phẩm
                </a>

                <a href="<?php echo url('Category'); ?>" class="nav-btn">
                    <i class="bi bi-tags"></i> Danh mục
                </a>

                <a href="<?php echo url('Category/add'); ?>" class="nav-btn">
                    <i class="bi bi-folder-plus"></i> Thêm danh mục
                </a>
            </nav>
        </div>

        <main class="col-md-9 col-lg-10 p-4 p-lg-5">
