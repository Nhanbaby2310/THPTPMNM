<?php
class SessionHelper
{
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function isLoggedIn()
    {
        self::start();
        return isset($_SESSION['username']);
    }

    public static function isAdmin()
    {
        self::start();
        return isset($_SESSION['username'], $_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    public static function hasRole($role)
    {
        self::start();
        return isset($_SESSION['role']) && $_SESSION['role'] === $role;
    }

    public static function getRole()
    {
        self::start();
        return $_SESSION['role'] ?? 'guest';
    }

    public static function requireLogin()
    {
        self::start();
        if (!self::isLoggedIn()) {
            header('Location: ' . (function_exists('url') ? url('Account/login') : '/THPTPMNM/Account/login'));
            exit();
        }
    }

    public static function requireAdmin()
    {
        self::start();
        if (!self::isAdmin()) {
            http_response_code(403);
            echo "Bạn không có quyền truy cập chức năng này!";
            exit();
        }
    }
}
