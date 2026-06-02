<?php include 'app/views/shares/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="mb-3 text-center">Đăng ký tài khoản</h2>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $err): ?>
                                <li><?php echo htmlspecialchars($err, ENT_QUOTES, 'UTF-8'); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?php echo url('Account/save'); ?>" method="post">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Họ tên</label>
                        <input type="text" name="fullname" class="form-control" value="<?php echo htmlspecialchars($_POST['fullname'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mật khẩu</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nhập lại mật khẩu</label>
                        <input type="password" name="confirmpassword" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Vai trò</label>
                        <select name="role" class="form-control">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                        <small class="text-muted">Khi demo bài 4, tạo ít nhất 1 tài khoản admin để kiểm tra phân quyền.</small>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
                </form>

                <p class="text-center mt-3 mb-0">
                    Đã có tài khoản? <a href="<?php echo url('Account/login'); ?>">Đăng nhập</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
