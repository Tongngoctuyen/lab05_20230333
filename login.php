<?php
session_start();
require_once 'includes/users.php';
require_once 'includes/flash.php';

$remembered = $_COOKIE['remember_username'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Vui lòng nhập đầy đủ thông tin.';
    } elseif (!empty($users[$username]) &&
        password_verify($password, $users[$username]['hash'])) {

        $_SESSION['auth'] = true;
        $_SESSION['user'] = [
            'username' => $username,
            'role' => $users[$username]['role']
        ];
require_once 'includes/logger.php';
write_log('LOGIN', $username);

        if (!empty($_POST['remember'])) {
            setcookie('remember_username', $username, time()+7*86400, '/');
        }

        set_flash('success', 'Đăng nhập thành công');
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Sai tài khoản hoặc mật khẩu.';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-secondary d-flex justify-content-center align-items-center vh-100">

<form method="post" class="bg-white p-4 rounded shadow" style="width:360px">
<h4 class="text-center text-primary">SHOP DEMO LOGIN</h4>

<?php if (!empty($error)): ?>
<div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<input class="form-control mb-2" name="username" placeholder="Username"
       value="<?= htmlspecialchars($remembered) ?>">
<input type="password" class="form-control mb-2" name="password" placeholder="Password">

<div class="form-check mb-3">
<input class="form-check-input" type="checkbox" name="remember">
<label class="form-check-label">Remember me</label>
</div>

<button class="btn btn-primary w-100">Login</button>
</form>

</body>
</html>
