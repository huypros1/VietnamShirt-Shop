<?php 
// Bảo vệ biến tránh lỗi undefined
$displayUsers = $displayUsers ?? [];
$keyword      = $keyword ?? '';
$page         = $page ?? 1;
$totalPages   = $totalPages ?? 1;
?>

<?php include_once "./Views/admin/layout_sidebar_admin.php"; ?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Người dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { background-color: #f8f9fa; }
        .card { box-shadow: 0 4px 12px rgba(0,0,0,0.1); border: none; border-radius: 12px; }
        .table thead { background-color: #343a40; color: white; }
        .badge { font-size: 0.85em; padding: 0.6em 1em; border-radius: 50px; font-weight: 500; }
        .btn-sm i { margin-right: 4px; }
        h2 { color: #343a40; font-weight: 600; }
        .avatar-placeholder {
            width: 35px; height: 35px; background: #e9ecef; 
            border-radius: 50%; display: inline-flex; 
            align-items: center; justify-content: center; color: #6c757d; margin-right: 10px;
        }
    </style>
</head>
<body class="container-fluid mt-4" style="padding-left: 20px;">

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-users me-2"></i> Quản lý Người dùng</h2>
            <a href="admin.php?ctrl=admin&act=user_add" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus"></i> Thêm người dùng
            </a>
        </div>

        <form method="get" action="admin.php" class="mb-4">
            <input type="hidden" name="ctrl" value="admin">
            <input type="hidden" name="act" value="users"> <div class="input-group" style="max-width: 500px;">
                <input type="text" name="keyword" value="<?php echo htmlspecialchars($keyword); ?>" 
                       placeholder="Tìm theo tên hoặc email..." class="form-control shadow-sm">
                <button type="submit" class="btn btn-secondary shadow-sm">
                    <i class="fas fa-search"></i> Tìm
                </button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th width="60">ID</th>
                        <th>Thông tin tài khoản</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th width="280" class="text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($displayUsers)): ?>
                        <?php foreach ($displayUsers as $user): ?>
                        <?php 
                            // Xử lý Logic hiển thị
                            $status = $user['status'] ?? 1; // Mặc định là 1 (Active) nếu không có cột status
                            $isBanned = ($status == 0);
                            
                            // Badge trạng thái
                            $statusLabel = $isBanned ? 'Bị khóa' : 'Hoạt động';
                            $statusClass = $isBanned ? 'bg-danger-subtle text-danger border border-danger' : 'bg-success-subtle text-success border border-success';
                            
                            // Nút thao tác Cấm/Mở
                            $banText = $isBanned ? 'Mở khóa' : 'Khóa';
                            $banClass = $isBanned ? 'btn-outline-success' : 'btn-outline-danger';
                            $banIcon = $isBanned ? 'fa-unlock' : 'fa-lock';
                            $confirmMsg = $isBanned 
                                ? 'Mở khóa tài khoản này?' 
                                : 'Bạn có chắc chắn muốn KHÓA tài khoản này không?';
                            
                            // Badge Role
                            $roleClass = ($user['role'] == 'admin') ? 'bg-primary' : 'bg-secondary';
                        ?>
                        <tr>
                            <td>#<?php echo $user['id']; ?></td>
                            
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark"><?php echo htmlspecialchars($user['name']); ?></div>
                                        <div class="text-muted small"><?php echo htmlspecialchars($user['email']); ?></div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="badge <?php echo $roleClass; ?>">
                                    <?php echo ucfirst($user['role']); ?>
                                </span>
                            </td>

                            <td>
                                <span class="badge <?php echo $statusClass; ?>">
                                    <?php echo $statusLabel; ?>
                                </span>
                            </td>

                            <td class="text-end">
                                <a href="admin.php?ctrl=admin&act=user_edit&id=<?php echo $user['id']; ?>" 
                                   class="btn btn-outline-primary btn-sm me-1" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <a href="admin.php?ctrl=admin&act=user_toggle_status&id=<?php echo $user['id']; ?>" 
                                   class="btn <?php echo $banClass; ?> btn-sm me-1"
                                   onclick="return confirm('<?php echo $confirmMsg; ?>')"
                                   title="<?php echo $banText; ?> tài khoản">
                                    <i class="fas <?php echo $banIcon; ?>"></i>
                                </a>

                                <a href="admin.php?ctrl=admin&act=user_delete&id=<?php echo $user['id']; ?>" 
                                   onclick="return confirm('CẢNH BÁO: Xóa vĩnh viễn người dùng <?php echo htmlspecialchars($user['name']); ?>?\nHành động này không thể hoàn tác!');"
                                   class="btn btn-outline-danger btn-sm" title="Xóa vĩnh viễn">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-search fa-3x mb-3 text-secondary opacity-50"></i><br>
                                <?php echo $keyword ? 'Không tìm thấy kết quả nào cho: "'.htmlspecialchars($keyword).'"' : 'Chưa có người dùng nào trong hệ thống.'; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($totalPages > 1): ?>
        <nav aria-label="Page navigation" class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="admin.php?ctrl=admin&act=users&page=<?php echo $page-1; ?>&keyword=<?php echo urlencode($keyword); ?>">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </li>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                    <a class="page-link" href="admin.php?ctrl=admin&act=users&page=<?php echo $i; ?>&keyword=<?php echo urlencode($keyword); ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
                <?php endfor; ?>

                <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="admin.php?ctrl=admin&act=users&page=<?php echo $page+1; ?>&keyword=<?php echo urlencode($keyword); ?>">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>

</body>
</html>