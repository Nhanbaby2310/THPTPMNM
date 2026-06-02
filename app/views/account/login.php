<?php include 'app/views/shares/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="mb-3 text-center">Đăng nhập</h2>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                <?php endif; ?>

                <!-- Đăng nhập thường -->
                <form action="<?php echo url('Account/checkLogin'); ?>" method="post">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mật khẩu</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
                    </button>
                </form>

                <!-- Đường phân cách -->
                <div class="d-flex align-items-center my-4">
                    <hr class="flex-grow-1">
                    <span class="mx-3 text-muted small">hoặc</span>
                    <hr class="flex-grow-1">
                </div>

                <!-- Đăng nhập mạng xã hội -->
                <a href="<?php echo url('Account/googleLogin'); ?>" class="btn btn-danger w-100 mb-2">
                    <i class="bi bi-google"></i> Đăng nhập bằng Google
                </a>

                <a href="<?php echo url('Account/githubLogin'); ?>" class="btn btn-dark w-100">
                    <i class="bi bi-github"></i> Đăng nhập bằng GitHub
                </a>

                <p class="text-center mt-3 mb-0">
                    Chưa có tài khoản?
                    <a href="<?php echo url('Account/register'); ?>">Đăng ký</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>