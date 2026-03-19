<?php 
// $user là biến được truyền từ controller, chứa thông tin người dùng cần sửa
if (!isset($user)) {
    header("Location: admin.php?ctrl=admin&act=users");
    exit;
}
?>

<?php include_once "./Views/admin/layout_sidebar_admin.php"; ?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa người dùng: <?php echo htmlspecialchars($user['name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .card { box-shadow: 0 4px 12px rgba(0,0,0,0.1); border: none; border-radius: 12px; }
    </style>
</head>
<body class="container mt-4">

<div class="card p-5">
    <h2 class="mb-4"><i class="fas fa-user-edit"></i> Sửa thông tin người dùng</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <form action="admin.php?ctrl=admin&act=user_edit_save" method="post">
        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

        <div class="mb-3">
            <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Mật khẩu mới (để trống nếu không đổi)</label>
            <input type="password" name="password" class="form-control" minlength="6">
            <small class="text-muted">Để trống nếu không muốn thay đổi mật khẩu</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Quyền</label>
            <select name="role" class="form-select">
                <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Trạng thái</label>
            <select name="status" class="form-select">
                <option value="1" <?php echo $user['status'] == 1 ? 'selected' : ''; ?>>Hoạt động</option>
                <option value="0" <?php echo $user['status'] == 0 ? 'selected' : ''; ?>>Bị cấm</option>
            </select>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-warning btn-lg"><i class="fas fa-save"></i> Cập nhật</button>
            <a href="admin.php?ctrl=admin&act=user" class="btn btn-secondary btn-lg ms-2">Quay lại</a>
        </div>
    </form>
</div>

</body>
</html> 