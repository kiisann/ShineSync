<?php
// app/core/Controller.php — Base Controller
class Controller
{
    protected Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /** Render view file dengan data */
    protected function view(string $viewPath, array $data = []): void
    {
        extract($data);
        $file = __DIR__ . '/../views/' . $viewPath . '.php';
        if (!file_exists($file)) {
            die("View tidak ditemukan: {$viewPath}");
        }
        require $file;
    }

    /** Redirect ke URL */
    protected function redirect(string $url): void
    {
        header('Location: ' . APP_URL . '/' . ltrim($url, '/'));
        exit;
    }

    /** Kirim JSON response */
    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /** Validasi input wajib */
    protected function validate(array $data, array $rules): array
    {
        $errors = [];
        foreach ($rules as $field => $rule) {
            if (str_contains($rule, 'required') && empty($data[$field])) {
                $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' wajib diisi.';
            }
        }
        return $errors;
    }

    /** Cek apakah request adalah AJAX */
    protected function isAjax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /** Cek metode HTTP */
    protected function isPost(): bool { return $_SERVER['REQUEST_METHOD'] === 'POST'; }
    protected function isGet(): bool  { return $_SERVER['REQUEST_METHOD'] === 'GET'; }
}
