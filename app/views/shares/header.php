<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopManager - Quản lý cửa hàng</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo url('public/assets/style.css'); ?>" rel="stylesheet">
</head>

<body>

<!-- Top Navigation Bar -->
<header class="top-navbar">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <a href="<?php echo url(''); ?>" class="navbar-brand">
            <i class="bi bi-lightning-charge-fill"></i> ShopManager
        </a>

        <nav>
            <ul class="navbar-nav">
                <li><a href="<?php echo url(''); ?>" class="nav-link"><i class="bi bi-grid-1x2"></i> Dashboard</a></li>
                <li><a href="<?php echo url('Product'); ?>" class="nav-link"><i class="bi bi-bag"></i> San pham</a></li>
                <li><a href="<?php echo url('Product/add'); ?>" class="nav-link"><i class="bi bi-plus-square"></i> Them SP</a></li>
                <li>
                    <a href="<?php echo url('Product/cart'); ?>" class="nav-link">
                        <i class="bi bi-cart3"></i> Gio hang
                        <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                            <span class="cart-badge"><?php echo count($_SESSION['cart']); ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li><a href="<?php echo url('Category'); ?>" class="nav-link"><i class="bi bi-bookmark"></i> Danh muc</a></li>
                <li><a href="<?php echo url('Category/add'); ?>" class="nav-link"><i class="bi bi-bookmark-plus"></i> Them DM</a></li>
            </ul>
        </nav>
    </div>
</header>

<!-- Main Content -->
<div class="content-wrapper">
