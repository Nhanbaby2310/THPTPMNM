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

<!-- Top Navbar -->
<nav class="top-nav">
    <div class="container-fluid d-flex align-items-center justify-content-between">
        <a href="<?php echo url(''); ?>" class="nav-brand">
            <i class="bi bi-shop-window"></i> COS340
        </a>

        <div class="nav-links">
            <a href="<?php echo url(''); ?>" class="nav-item">
                <i class="bi bi-house-door"></i> Trang chủ
            </a>
            <a href="<?php echo url('Product'); ?>" class="nav-item">
                <i class="bi bi-box-seam"></i> Sản phẩm
            </a>
            <a href="<?php echo url('Product/add'); ?>" class="nav-item">
                <i class="bi bi-plus-circle"></i> Thêm sản phẩm
            </a>
            <a href="<?php echo url('Product/cart'); ?>" class="nav-item">
                <i class="bi bi-cart3"></i> Giỏ hàng
                <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                    <span class="badge bg-danger rounded-pill"><?php echo count($_SESSION['cart']); ?></span>
                <?php endif; ?>
            </a>
            <a href="<?php echo url('Category'); ?>" class="nav-item">
                <i class="bi bi-tags"></i> Danh mục
            </a>
            <a href="<?php echo url('Category/add'); ?>" class="nav-item">
                <i class="bi bi-folder-plus"></i> Thêm danh mục
            </a>
        </div>
    </div>
</nav>

<!-- Main Content -->
<main class="main-content">
    <div class="container-fluid px-4 px-lg-5 py-4">
