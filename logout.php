<?php
require_once 'includes/auth.php';
require_once 'includes/csrf.php';
require_once 'includes/flash.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' ||
    !csrf_verify($_POST['csrf'] ?? null)) {
    http_response_code(400);
    exit('Bad Request');
}
require_once 'includes/logger.php';
$username = $_SESSION['user']['username'] ?? 'unknown';
write_log('LOGOUT', $username);

session_unset();
session_destroy();

setcookie('remember_username', '', time() - 3600, '/');

session_start();
set_flash('info', 'Bạn đã đăng xuất');

header('Location: login.php');
exit;
