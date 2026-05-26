<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopManager - Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo url('public/assets/style.css'); ?>" rel="stylesheet">
</head>

<body>
<section class="hero-section">
    <div class="hero-container">
        <div class="hero-box">
            <span style="display:inline-block; background: var(--primary-gradient); color:#fff; padding: 8px 20px; border-radius: var(--radius-full); font-size: 0.85rem; font-weight: 600; margin-bottom: 1.5rem;">
                <i class="bi bi-rocket-takeoff"></i> He thong quan ly cua hang
            </span>

            <h1>ShopManager</h1>

            <p>
                Ung dung quan ly san pham va danh muc su dung PHP MVC voi MySQL.
                Ho tro CRUD day du, upload hinh anh va phan quyen danh muc.
            </p>

            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="<?php echo url('Product'); ?>" class="btn-primary-custom">
                    <i class="bi bi-bag"></i> Quan ly san pham
                </a>

                <a href="<?php echo url('Category'); ?>" class="btn-secondary-custom">
                    <i class="bi bi-bookmark"></i> Quan ly danh muc
                </a>
            </div>

            <div class="hero-features">
                <div class="hero-feature">
                    <i class="bi bi-box-seam"></i>
                    <h5>CRUD San pham</h5>
                    <p>Them, xem chi tiet, cap nhat va xoa san pham mot cach de dang.</p>
                </div>

                <div class="hero-feature">
                    <i class="bi bi-diagram-3"></i>
                    <h5>Quan ly Danh muc</h5>
                    <p>To chuc san pham theo danh muc, ho tro cascade delete.</p>
                </div>

                <div class="hero-feature">
                    <i class="bi bi-cloud-arrow-up"></i>
                    <h5>Upload Hinh anh</h5>
                    <p>Tai anh len server, chon anh co san hoac upload moi.</p>
                </div>
            </div>
        </div>

        <p style="text-align:center; margin-top: 2rem; color: var(--text-muted); font-size: 0.85rem;">
            Thuc hanh phat trien phan mem ma nguon mo &mdash; HUTECH
        </p>
    </div>
</section>
</body>
</html>
