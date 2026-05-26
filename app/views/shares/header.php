<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web bán hàng Nhân</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo url('public/assets/style.css'); ?>" rel="stylesheet">
</head>

<body>

<!-- Top Navigation Bar -->
<header class="top-navbar">
    <a href="<?php echo url(''); ?>" class="navbar-brand">
        <i class="bi bi-lightning-charge-fill"></i> Web bán hàng Nhân
    </a>

    <div class="navbar-menu">
        <a href="<?php echo url(''); ?>" class="nav-item"><i class="bi bi-house"></i> Trang chu</a>
        <a href="<?php echo url('Product'); ?>" class="nav-item"><i class="bi bi-bag"></i> San pham</a>
        <a href="<?php echo url('Product/add'); ?>" class="nav-item"><i class="bi bi-plus-square"></i> Them SP</a>
        <a href="<?php echo url('Product/cart'); ?>" class="nav-item">
            <i class="bi bi-cart3"></i> Gio hang
            <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                <span class="cart-badge"><?php echo count($_SESSION['cart']); ?></span>
            <?php endif; ?>
        </a>
        <a href="<?php echo url('Category'); ?>" class="nav-item"><i class="bi bi-bookmark"></i> Danh muc</a>
        <a href="<?php echo url('Category/add'); ?>" class="nav-item"><i class="bi bi-bookmark-plus"></i> Them DM</a>
    </div>
</header>

<!-- Main Content -->
<div class="content-wrapper">
