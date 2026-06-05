<?php
// app/controllers/AuthController.php
class AuthController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
    }

    public function login(): void
    {
        if (Session::isLoggedIn()) {
            $this->redirect(Session::isAdmin() ? 'admin/dashboard' : '');
            return;
        }

        $error = Session::getFlash('error');
        $success = Session::getFlash('success');

        if ($this->isPost()) {
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                if (!$user['is_active']) {
                    Session::flash('error', 'Akun Anda telah dinonaktifkan.');
                    $this->redirect('auth/login');
                    return;
                }
                if ($user['role'] === 'admin') {
                    Session::flash('error', 'Gunakan halaman login admin.');
                    $this->redirect('auth/login');
                    return;
                }
                Session::login($user);
                $this->redirect('');
            } else {
                Session::flash('error', 'Email atau password salah.');
                $this->redirect('auth/login');
            }
            return;
        }

        $this->view('auth/login', compact('error', 'success'));
    }

    public function adminLogin(): void
    {
        if (Session::isAdmin()) {
            $this->redirect('admin/dashboard');
            return;
        }

        $error = Session::getFlash('error');

        if ($this->isPost()) {
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $user     = $this->userModel->findByEmail($email);

            if ($user && $user['role'] === 'admin' && password_verify($password, $user['password'])) {
                Session::login($user);
                $this->redirect('admin/dashboard');
            } else {
                Session::flash('error', 'Email atau password admin salah.');
                $this->redirect('admin/login');
            }
            return;
        }

        $this->view('auth/admin_login', compact('error'));
    }

    public function register(): void
    {
        if (Session::isLoggedIn()) {
            $this->redirect('');
            return;
        }

        $error   = Session::getFlash('error');
        $success = Session::getFlash('success');

        if ($this->isPost()) {
            $name     = trim($_POST['name'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm  = $_POST['password_confirm'] ?? '';
            $phone    = trim($_POST['phone'] ?? '');

            if (empty($name) || empty($email) || empty($password)) {
                Session::flash('error', 'Semua field wajib diisi.');
                $this->redirect('auth/register');
                return;
            }
            if ($password !== $confirm) {
                Session::flash('error', 'Konfirmasi password tidak cocok.');
                $this->redirect('auth/register');
                return;
            }
            if (strlen($password) < 6) {
                Session::flash('error', 'Password minimal 6 karakter.');
                $this->redirect('auth/register');
                return;
            }
            if ($this->userModel->emailExists($email)) {
                Session::flash('error', 'Email sudah terdaftar.');
                $this->redirect('auth/register');
                return;
            }

            $this->userModel->create([
                'name'     => $name,
                'email'    => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'phone'    => $phone,
                'role'     => 'customer'
            ]);

            Session::flash('success', 'Registrasi berhasil! Silakan login.');
            $this->redirect('auth/login');
            return;
        }

        $this->view('auth/register', compact('error', 'success'));
    }

    public function logout(): void
    {
        Session::logout();
        $this->redirect('auth/login');
    }
}
