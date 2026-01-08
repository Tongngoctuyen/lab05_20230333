<?php
require_once 'includes/auth.php';
require_login();

require_once 'includes/products.php';
require_once 'includes/cart.php';
require_once 'includes/csrf.php';
require_once 'includes/header.php';

$user = $_SESSION['user'];
$cartCount = array_sum(cart_items());
?>

<h3 class="mb-3">
    👋 Xin chào,
    <span class="text-primary">
        <?= htmlspecialchars($user['username']) ?>
    </span>
</h3>

<p>
    Quyền truy cập:
    <span class="badge bg-info">
        <?= htmlspecialchars($user['role']) ?>
    </span>
</p>

<!-- Thống kê nhanh -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-primary">
            <div class="card-body text-center">
                <h6>Sản phẩm</h6>
                <h3 class="text-primary"><?= count($products) ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-success">
            <div class="card-body text-center">
                <h6>Trong giỏ hàng</h6>
                <h3 class="text-success"><?= $cartCount ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-warning">
            <div class="card-body text-center">
                <h6>Trạng thái</h6>
                <h5 class="text-warning">Đang hoạt động</h5>
            </div>
        </div>
    </div>
</div>

<!-- Sản phẩm nổi bật -->
<h4 class="mb-3">⭐ Sản phẩm nổi bật</h4>

<div class="row">
<?php
$featured = $products;
foreach ($featured as $id => $p):
?>
    <div class="col-md-4">
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <h5><?= htmlspecialchars($p['name']) ?></h5>
                <p class="text-danger fw-bold">
                    <?= number_format($p['price']) ?> đ
                </p>
                <a href="products.php" class="btn btn-outline-primary btn-sm">
                    Xem sản phẩm
                </a>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>

<!-- Admin Panel -->
<?php if ($user['role'] === 'admin'): ?>
<div class="card border-warning mt-4">
    <div class="card-body">
        <h5 class="text-warning">Admin Panel</h5>
        <p>Chức năng dành riêng cho quản trị viên</p>
        <a href="admin_logs.php" class="btn btn-warning btn-sm">
            Xem log hệ thống
        </a>
    </div>
</div>
<?php endif; ?>

<!-- Logout -->
<form method="post" action="logout.php" class="mt-4">
    <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
    <button class="btn btn-danger">Đăng xuất</button>
</form>

<?php require_once 'includes/footer.php'; ?>
