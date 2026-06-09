<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <div class="page-header">
        <h1>Quản lý sản phẩm bằng RESTful API</h1>
        <p>Trang front-end sử dụng jQuery AJAX để gọi API sản phẩm</p>
    </div>

    <!-- Thông báo -->
    <div id="message"></div>

    <!-- Form thêm / sửa sản phẩm -->
    <div class="card-modern mb-4">
        <div class="card-header-modern">
            <h5 id="formTitle" style="margin: 0; font-weight: 600;">
                <i class="bi bi-plus-circle"></i> Thêm sản phẩm bằng API
            </h5>
        </div>

        <div class="p-4">
            <input type="hidden" id="productId">

            <div class="mb-3">
                <label class="form-label">Tên sản phẩm</label>
                <input type="text" id="name" class="form-control" placeholder="Nhập tên sản phẩm">
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea id="description" class="form-control" rows="3" placeholder="Nhập mô tả sản phẩm"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Giá</label>
                <input type="number" id="price" class="form-control" placeholder="Nhập giá sản phẩm">
            </div>

            <div class="mb-3">
                <label class="form-label">Mã danh mục</label>
                <input type="number" id="category_id" class="form-control" placeholder="Ví dụ: 1">
            </div>

            <div class="mb-3">
                <label class="form-label">Hình ảnh</label>
                <input type="text" id="image" class="form-control" placeholder="Tên file hình hoặc để trống">
            </div>

            <button id="btnSave" class="btn btn-primary">
                <i class="bi bi-save"></i> Lưu sản phẩm
            </button>

            <button id="btnReset" class="btn btn-secondary">
                <i class="bi bi-arrow-clockwise"></i> Làm mới
            </button>
        </div>
    </div>

    <!-- Danh sách sản phẩm -->
    <div class="card-modern">
        <div class="card-header-modern">
            <h5 style="margin: 0; font-weight: 600;">
                <i class="bi bi-list-ul"></i> Danh sách sản phẩm từ API
            </h5>

            <button id="btnLoad" class="btn btn-success btn-sm">
                <i class="bi bi-arrow-repeat"></i> Tải lại
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 70px;">ID</th>
                        <th>Tên sản phẩm</th>
                        <th>Mô tả</th>
                        <th style="width: 120px;">Giá</th>
                        <th style="width: 150px;">Danh mục</th>
                        <th style="width: 180px;">Thao tác</th>
                    </tr>
                </thead>

                <tbody id="productTable">
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Đang tải dữ liệu...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    const API_URL = "<?php echo url('api/product'); ?>";

    $(document).ready(function () {
        loadProducts();

        $('#btnLoad').click(function () {
            loadProducts();
        });

        $('#btnSave').click(function () {
            saveProduct();
        });

        $('#btnReset').click(function () {
            resetForm();
        });
    });

    // =========================
    // GET /api/product
    // =========================
    function loadProducts() {
        $.ajax({
            url: API_URL,
            type: 'GET',
            dataType: 'json',
            success: function (products) {
                let html = '';

                if (products.length === 0) {
                    html = `
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Chưa có sản phẩm nào
                            </td>
                        </tr>
                    `;
                } else {
                    products.forEach(function (product) {
                        html += `
                            <tr>
                                <td>${product.id}</td>
                                <td>
                                    <strong>${escapeHtml(product.name)}</strong>
                                </td>
                                <td>${escapeHtml(product.description ?? '')}</td>
                                <td>${Number(product.price).toLocaleString('vi-VN')} đ</td>
                                <td>${escapeHtml(product.category_name ?? '')}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm" onclick="editProduct(${product.id})">
                                        <i class="bi bi-pencil"></i> Sửa
                                    </button>

                                    <button class="btn btn-danger btn-sm" onclick="deleteProduct(${product.id})">
                                        <i class="bi bi-trash"></i> Xóa
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                }

                $('#productTable').html(html);
            },
            error: function (xhr) {
                showMessage('Không thể tải danh sách sản phẩm.', 'danger');
                console.log(xhr.responseText);
            }
        });
    }

    // =========================
    // GET /api/product/{id}
    // =========================
    function editProduct(id) {
        $.ajax({
            url: API_URL + '/' + id,
            type: 'GET',
            dataType: 'json',
            success: function (product) {
                $('#productId').val(product.id);
                $('#name').val(product.name);
                $('#description').val(product.description);
                $('#price').val(product.price);
                $('#category_id').val(product.category_id ?? '');
                $('#image').val(product.image ?? '');

                $('#formTitle').html('<i class="bi bi-pencil-square"></i> Cập nhật sản phẩm bằng API');
                $('#btnSave').html('<i class="bi bi-save"></i> Cập nhật sản phẩm');

                $('html, body').animate({
                    scrollTop: 0
                }, 300);
            },
            error: function (xhr) {
                showMessage('Không tìm thấy sản phẩm cần sửa.', 'danger');
                console.log(xhr.responseText);
            }
        });
    }

    // =========================
    // POST /api/product
    // PUT /api/product/{id}
    // =========================
    function saveProduct() {
        let id = $('#productId').val();

        let productData = {
            name: $('#name').val(),
            description: $('#description').val(),
            price: $('#price').val(),
            category_id: $('#category_id').val(),
            image: $('#image').val()
        };

        let method = id ? 'PUT' : 'POST';
        let url = id ? API_URL + '/' + id : API_URL;

        $.ajax({
            url: url,
            type: method,
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify(productData),
            success: function (response) {
                showMessage(response.message, 'success');
                resetForm();
                loadProducts();
            },
            error: function (xhr) {
                let response = xhr.responseJSON;

                if (response && response.errors) {
                    showMessage(response.errors.join('<br>'), 'danger');
                } else if (response && response.message) {
                    showMessage(response.message, 'danger');
                } else {
                    showMessage('Có lỗi xảy ra khi lưu sản phẩm.', 'danger');
                }

                console.log(xhr.responseText);
            }
        });
    }

    // =========================
    // DELETE /api/product/{id}
    // =========================
    function deleteProduct(id) {
        if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?')) {
            return;
        }

        $.ajax({
            url: API_URL + '/' + id,
            type: 'DELETE',
            dataType: 'json',
            success: function (response) {
                showMessage(response.message, 'success');
                loadProducts();
            },
            error: function (xhr) {
                let response = xhr.responseJSON;

                if (response && response.message) {
                    showMessage(response.message, 'danger');
                } else {
                    showMessage('Không thể xóa sản phẩm.', 'danger');
                }

                console.log(xhr.responseText);
            }
        });
    }

    function resetForm() {
        $('#productId').val('');
        $('#name').val('');
        $('#description').val('');
        $('#price').val('');
        $('#category_id').val('');
        $('#image').val('');

        $('#formTitle').html('<i class="bi bi-plus-circle"></i> Thêm sản phẩm bằng API');
        $('#btnSave').html('<i class="bi bi-save"></i> Lưu sản phẩm');
    }

    function showMessage(message, type) {
        $('#message').html(`
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);
    }

    function escapeHtml(text) {
        if (text === null || text === undefined) {
            return '';
        }

        return String(text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
</script>

<?php include 'app/views/shares/footer.php'; ?>