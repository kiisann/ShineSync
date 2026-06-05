<?php
// app/controllers/ProfileController.php
class ProfileController extends Controller
{
    private User $model;

    public function __construct() { parent::__construct(); $this->model = new User(); }

    public function index(): void
    {
        Session::requireCustomer();
        $user      = $this->model->findById(Session::get('user_id'));
        $success   = Session::getFlash('success');
        $error     = Session::getFlash('error');
        $pageTitle = 'Profil Saya — ShineSync';
        $this->view('customer/profile', compact('user', 'success', 'error', 'pageTitle'));
    }

    public function update(): void
    {
        Session::requireCustomer();
        $userId = Session::get('user_id');
        $name   = trim($_POST['name'] ?? '');
        $phone  = trim($_POST['phone'] ?? '');
        $address= trim($_POST['address'] ?? '');

        if (empty($name)) {
            Session::flash('error', 'Nama tidak boleh kosong.');
            $this->redirect('profile');
            return;
        }

        // Upload avatar
        if (!empty($_FILES['avatar']['name'])) {
            $ext  = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','webp'])) {
                $fn = 'avatar_' . $userId . '_' . time() . '.' . $ext;
                $dest = UPLOAD_PATH . 'avatars/';
                if (!is_dir($dest)) mkdir($dest, 0777, true);
                move_uploaded_file($_FILES['avatar']['tmp_name'], $dest . $fn);
                $this->model->updateAvatar($userId, $fn);
                Session::set('user_avatar', $fn);
            }
        }

        $this->model->update($userId, compact('name', 'phone', 'address'));
        Session::set('user_name', $name);
        Session::flash('success', 'Profil berhasil diperbarui.');
        $this->redirect('profile');
    }

    public function changePassword(): void
    {
        Session::requireCustomer();
        $userId  = Session::get('user_id');
        $user    = $this->model->findById($userId);
        $current = $_POST['current_password'] ?? '';
        $new     = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        $userFull = $this->model->findByEmail($user['email']);
        if (!password_verify($current, $userFull['password'])) {
            Session::flash('error', 'Password lama tidak benar.');
            $this->redirect('profile');
            return;
        }
        if ($new !== $confirm || strlen($new) < 6) {
            Session::flash('error', 'Password baru tidak cocok atau terlalu pendek.');
            $this->redirect('profile');
            return;
        }
        $this->model->updatePassword($userId, password_hash($new, PASSWORD_DEFAULT));
        Session::flash('success', 'Password berhasil diubah.');
        $this->redirect('profile');
    }
}
