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
