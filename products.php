<?php
require_once 'includes/auth.php';
require_login();

require_once 'includes/products.php';
require_once 'includes/cart.php';
require_once 'includes/csrf.php';
require_once 'includes/flash.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf'] ?? null)) {
        die('CSRF invalid');
    }

    $id = (int)($_POST['product_id'] ?? 0);
    if (isset($products[$id])) {
        cart_add($id, 1);
        set_flash('success', 'Đã thêm sản phẩm vào giỏ');
    }

    header('Location: products.php');
    exit;
}

require_once 'includes/header.php';
?>

<h3 class="mb-3">Danh sách sản phẩm</h3>

<div class="row">
<?php foreach ($products as $id => $p): ?>
<div class="col-md-3">
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5><?= htmlspecialchars($p['name']) ?></h5>
            <p class="text-danger"><?= number_format($p['price']) ?> đ</p>
            <form method="post">
                <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
                <input type="hidden" name="product_id" value="<?= $id ?>">
                <button class="btn btn-primary btn-sm w-100">Add to cart</button>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
