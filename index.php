<?php
session_start();

// Front Controller - điều hướng theo mô hình MVC

$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$basePath = rtrim($scriptName, '/');

if ($basePath === '/' || $basePath === '.') {
    $basePath = '';
}

define('BASE_URL', $basePath);

function url($path = '')
{
    return BASE_URL . '/' . ltrim($path, '/');
}

$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);


// =====================================================
// BÀI 5 - API THƯỜNG, KHÔNG CẦN TOKEN
// URL:
// /api/product
// /api/category
// =====================================================
if (isset($url[0]) && strtolower($url[0]) === 'api') {
    header('Content-Type: application/json; charset=utf-8');

    $resource = strtolower($url[1] ?? '');
    $id = $url[2] ?? null;
    $method = $_SERVER['REQUEST_METHOD'];

    if ($resource === 'product') {
        require_once 'app/controllers/ProductApiController.php';

        $controller = new ProductApiController();

        switch ($method) {
            case 'GET':
                if ($id) {
                    $controller->show($id);
                } else {
                    $controller->index();
                }
                break;

            case 'POST':
                $controller->store();
                break;

            case 'PUT':
                if ($id) {
                    $controller->update($id);
                } else {
                    http_response_code(400);
                    echo json_encode(['message' => 'Product ID is required'], JSON_UNESCAPED_UNICODE);
                }
                break;

            case 'DELETE':
                if ($id) {
                    $controller->destroy($id);
                } else {
                    http_response_code(400);
                    echo json_encode(['message' => 'Product ID is required'], JSON_UNESCAPED_UNICODE);
                }
                break;

            default:
                http_response_code(405);
                echo json_encode(['message' => 'Method Not Allowed'], JSON_UNESCAPED_UNICODE);
                break;
        }

        exit;
    }

    if ($resource === 'category') {
        require_once 'app/controllers/CategoryApiController.php';

        $controller = new CategoryApiController();

        if ($method === 'GET') {
            $controller->index();
        } else {
            http_response_code(405);
            echo json_encode(['message' => 'Method Not Allowed'], JSON_UNESCAPED_UNICODE);
        }

        exit;
    }

    http_response_code(404);
    echo json_encode(['message' => 'API resource not found'], JSON_UNESCAPED_UNICODE);
    exit;
}


// =====================================================
// BÀI 6 - API BẢO MẬT JWT
// URL:
// /api-secure/auth/login
// /api-secure/product
// =====================================================
if (isset($url[0]) && strtolower($url[0]) === 'api-secure') {
    header('Content-Type: application/json; charset=utf-8');

    $resource = strtolower($url[1] ?? '');
    $id = $url[2] ?? null;
    $method = $_SERVER['REQUEST_METHOD'];

    if ($resource === 'auth') {
        require_once 'app/controllers/AuthApiController.php';

        $controller = new AuthApiController();
        $action = strtolower($url[2] ?? '');

        if ($method === 'POST' && $action === 'login') {
            $controller->login();
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Auth API not found'], JSON_UNESCAPED_UNICODE);
        }

        exit;
    }

    if ($resource === 'product') {
        require_once 'app/controllers/SecureProductApiController.php';

        $controller = new SecureProductApiController();

        switch ($method) {
            case 'GET':
                if ($id) {
                    $controller->show($id);
                } else {
                    $controller->index();
                }
                break;

            case 'POST':
                $controller->store();
                break;

            case 'PUT':
                if ($id) {
                    $controller->update($id);
                } else {
                    http_response_code(400);
                    echo json_encode(['message' => 'Product ID is required'], JSON_UNESCAPED_UNICODE);
                }
                break;

            case 'DELETE':
                if ($id) {
                    $controller->destroy($id);
                } else {
                    http_response_code(400);
                    echo json_encode(['message' => 'Product ID is required'], JSON_UNESCAPED_UNICODE);
                }
                break;

            default:
                http_response_code(405);
                echo json_encode(['message' => 'Method Not Allowed'], JSON_UNESCAPED_UNICODE);
                break;
        }

        exit;
    }

    http_response_code(404);
    echo json_encode(['message' => 'Secure API resource not found'], JSON_UNESCAPED_UNICODE);
    exit;
}


// =====================================================
// ROUTER MVC CŨ - GIỮ NGUYÊN CHO WEB
// =====================================================
$controllerName = isset($url[0]) && $url[0] != ''
    ? ucfirst($url[0]) . 'Controller'
    : 'DefaultController';

$action = isset($url[1]) && $url[1] != ''
    ? $url[1]
    : 'index';

$controllerFile = 'app/controllers/' . $controllerName . '.php';

if (!file_exists($controllerFile)) {
    die('Controller not found');
}

require_once $controllerFile;

$controller = new $controllerName();

if (!method_exists($controller, $action)) {
    die('Action not found');
}

call_user_func_array([$controller, $action], array_slice($url, 2));