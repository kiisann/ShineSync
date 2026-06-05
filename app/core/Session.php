<?php
// Session helper
class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            $savePath = session_save_path();
            if (!$savePath || !is_writable($savePath)) {
                $localPath = (defined('ROOT_PATH') ? ROOT_PATH : dirname(__DIR__, 2)) . '/storage/sessions';
                if (!is_dir($localPath)) {
                    mkdir($localPath, 0777, true);
                }
                session_save_path($localPath);
            }
            session_start();
        }
    }

    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function destroy(): void
    {
        session_destroy();
        $_SESSION = [];
    }

    public static function flash(string $key, string $message): void
    {
        $_SESSION['_flash'][$key] = $message;
    }

    public static function getFlash(string $key): ?string
    {
        $msg = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);
        return $msg;
    }

    // Auth Helpers

    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public static function isAdmin(): bool
    {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }

    public static function isCustomer(): bool
    {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'customer';
    }

    public static function login(array $user): void
    {
        $_SESSION['user_id']    = $user['id'];
        $_SESSION['user_name']  = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role']  = $user['role'];
        $_SESSION['user_avatar']= $user['avatar'] ?? 'default.png';
    }

    public static function logout(): void
    {
        self::destroy();
    }

    public static function requireLogin(): void
    {
        if (!self::isLoggedIn()) {
            header('Location: ' . APP_URL . '/auth/login');
            exit;
        }
    }

    public static function requireAdmin(): void
    {
        if (!self::isLoggedIn() || !self::isAdmin()) {
            header('Location: ' . APP_URL . '/admin/login');
            exit;
        }
    }

    public static function requireCustomer(): void
    {
        if (!self::isLoggedIn() || !self::isCustomer()) {
            header('Location: ' . APP_URL . '/auth/login');
            exit;
        }
    }
}
