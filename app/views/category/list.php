<?php include 'app/views/shares/header.php'; ?>

<!-- Page Header -->
<div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <h1>Danh muc san pham</h1>
        <p>Quan ly cac danh muc phan loai san pham</p>
    </div>

    <a href="<?php echo url('Category/add'); ?>" class="btn-primary-custom">
        <i class="bi bi-plus-lg"></i> Them danh muc moi
    </a>
</div>

<!-- Table -->
<div class="card-modern">
    <div class="card-header-modern">
        <h5 style="font-weight: 600; margin: 0;">
            <i class="bi bi-bookmark" style="color: var(--primary);"></i> Bang danh muc
        </h5>
        <span style="background: #eef2ff; color: var(--primary); padding: 6px 14px; border-radius: var(--radius-full); font-size: 0.8rem; font-weight: 600;">
            <?php echo count($categories); ?> danh muc
        </span>
    </div>

    <div style="overflow-x: auto;">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ten danh muc</th>
                    <th>Mo ta</th>
                    <th style="text-align: center;">Thao tac</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($categories)) : ?>
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 3rem; color: var(--text-muted);">
                            <i class="bi bi-folder2-open" style="font-size: 2rem; display: block; margin-bottom: 0.5rem;"></i>
                            Chua co danh muc nao
                        </td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($categories as $category) : ?>
                        <tr>
                            <td>
                                <span style="background: #f1f5f9; padding: 4px 12px; border-radius: var(--radius-full); font-size: 0.8rem; font-weight: 600;">
                                    #<?php echo htmlspecialchars($category->id, ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </td>
                            <td>
                                <strong style="color: var(--primary);">
                                    <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                                </strong>
                            </td>
                            <td style="color: var(--text-secondary);">
                                <?php echo htmlspecialchars($category->description, ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                            <td style="text-align: center;">
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="<?php echo url('Category/edit/' . $category->id); ?>" class="btn-icon edit" title="Sua">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?php echo url('Category/delete/' . $category->id); ?>" class="btn-icon delete" title="Xoa"
                                       onclick="return confirm('Xoa danh muc se xoa cac san pham thuoc danh muc nay. Ban co chac khong?');">
                                        <i class="bi bi-trash3"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
