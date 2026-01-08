<?php
require_once 'includes/auth.php';
require_login();

if ($_SESSION['user']['role'] !== 'admin') {
    exit('Access denied');
}

require_once 'includes/header.php';

$logFile = 'data/log.txt';
$logs = file_exists($logFile) ? file($logFile) : [];
?>

<h3>System Logs</h3>

<pre class="bg-dark text-light p-3 rounded">
<?php foreach ($logs as $line): ?>
<?= htmlspecialchars($line) ?>
<?php endforeach; ?>
</pre>

<?php require_once 'includes/footer.php'; ?>
