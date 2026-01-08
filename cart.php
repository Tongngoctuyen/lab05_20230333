<?php
require_once 'includes/auth.php';
require_login();

require_once 'includes/cart.php';
require_once 'includes/products.php';
require_once 'includes/csrf.php';
require_once 'includes/flash.php';

/* ================= XỬ LÝ POST ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!csrf_verify($_POST['csrf'] ?? null)) {
        die('CSRF invalid');
    }

    //  XÓA 1 DÒNG
    if (isset($_POST['remove_id'])) {
        cart_remove((int)$_POST['remove_id']);
        set_flash('info', 'Đã xóa sản phẩm khỏi giỏ hàng');
    }

    // cập nhật số lượng
    if (isset($_POST['update'])) {
        foreach ($_POST['qty'] as $id => $qty) {
            cart_update((int)$id, (int)$qty);
        }
        set_flash('success', 'Đã cập nhật giỏ hàng');
    }

    // xóa toàn bộ
    if (isset($_POST['clear'])) {
        cart_clear();
        set_flash('info', 'Đã xóa toàn bộ giỏ hàng');
    }

    header('Location: cart.php');
    exit;
}

/* ================= HIỂN THỊ ================= */
require_once 'includes/header.php';

$cart = cart_items();
$total = 0;
?>

<h3>Giỏ hàng</h3>

<?php if (empty($cart)): ?>
    <p>Giỏ hàng trống.</p>
<?php else: ?>

<form method="post">
    <input type="hidden" name="csrf" value="<?= csrf_token() ?>">

<table class="table table-bordered">
<tr class="table-primary">
    <th>Sản phẩm</th>
    <th>Giá</th>
    <th style="width:120px">Số lượng</th>
    <th>Thành tiền</th>
    <th style="width:90px">Xóa</th>
</tr>

<?php foreach ($cart as $id => $qty): 
    $p = $products[$id];
    $sub = $p['price'] * $qty;
    $total += $sub;
?>
<tr>
    <td><?= htmlspecialchars($p['name']) ?></td>
    <td><?= number_format($p['price']) ?> đ</td>

    <td>
        <input class="form-control" type="number"
               name="qty[<?= $id ?>]" value="<?= $qty ?>" min="1">
    </td>

    <td><?= number_format($sub) ?> đ</td>

    <!-- NÚT XÓA 1 DÒNG -->
    <td class="text-center">
        <button type="submit"
                name="remove_id"
                value="<?= $id ?>"
                class="btn btn-danger btn-sm">
            🗑
        </button>
    </td>
</tr>
<?php endforeach; ?>

<tr>
    <th colspan="3">Tổng cộng</th>
    <th class="text-danger"><?= number_format($total) ?> đ</th>
    <th></th>
</tr>
</table>

<button name="update" class="btn btn-success">Cập nhật</button>
<button name="clear" class="btn btn-danger">Xóa toàn bộ</button>
</form>

<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
