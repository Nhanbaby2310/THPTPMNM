<?php

require_once 'app/config/database.php';
require_once 'app/models/CategoryModel.php';
require_once 'app/helpers/SessionHelper.php';

class CategoryController
{
    private $categoryModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();

        if (!$this->db) {
            die('Không kết nối được database. Hãy chạy file setup_database.php trước.');
        }

        $this->categoryModel = new CategoryModel($this->db);
    }

    private function requireAdmin()
    {
        if (!SessionHelper::isAdmin()) {
            include 'app/views/shares/header.php';

            echo '
            <div class="container mt-5">
                <div class="alert alert-danger text-center shadow-sm">
                    <h4><i class="bi bi-shield-lock"></i> Không có quyền truy cập</h4>
                    <p>Bạn không có quyền thực hiện chức năng này. Chỉ tài khoản admin mới được thêm, sửa hoặc xóa danh mục.</p>
                    <a href="' . url('Category') . '" class="btn btn-primary mt-2">
                        Quay lại danh sách danh mục
                    </a>
                </div>
            </div>';

            include 'app/views/shares/footer.php';
            exit;
        }
    }

    public function index()
    {
        $categories = $this->categoryModel->getCategories();
        include 'app/views/category/list.php';
    }

    public function list()
    {
        $this->index();
    }

    public function add()
    {
        $this->requireAdmin();

        include 'app/views/category/add.php';
    }

    public function save()
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';

            $result = $this->categoryModel->addCategory($name, $description);

            if (is_array($result)) {
                $errors = $result;
                include 'app/views/category/add.php';
            } else {
                header('Location: ' . url('Category'));
                exit();
            }
        }
    }

    public function edit($id)
    {
        $this->requireAdmin();

        $category = $this->categoryModel->getCategoryById($id);

        if ($category) {
            include 'app/views/category/edit.php';
        } else {
            echo "Không thấy danh mục.";
        }
    }

    public function update()
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? '';
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';

            $result = $this->categoryModel->updateCategory($id, $name, $description);

            if (is_array($result)) {
                $errors = $result;
                $category = $this->categoryModel->getCategoryById($id);
                include 'app/views/category/edit.php';
            } else {
                header('Location: ' . url('Category'));
                exit();
            }
        }
    }

    public function delete($id)
    {
        $this->requireAdmin();

        if ($this->categoryModel->deleteCategory($id)) {
            header('Location: ' . url('Category'));
            exit();
        }

        echo "Đã xảy ra lỗi khi xóa danh mục.";
    }
}