<?php
session_start();
include_once './Controllers/AdminController.php';

// 1. XỬ LÝ KHI CÓ CONTROLLER RIÊNG (VD: ?ctrl=user...)
if (isset($_GET['ctrl']) && isset($_GET['act'])) {
    $ctrl = $_GET['ctrl'];
    $act  = $_GET['act'];
    $ctrlName = ucfirst($ctrl) . 'Controller';
    $path = "Controllers/$ctrlName.php";
    
    if (file_exists($path)) {
        include_once $path;
        
        if (class_exists($ctrlName)) {
            $controller = new $ctrlName();
            
            // Kiểm tra xem method có tồn tại không trước khi gọi
            if (method_exists($controller, $act)) {
                if (!empty($_GET['id'])) {
                    $controller->$act($_GET['id']);
                } else {
                    $controller->$act();
                }
            } else {
                // Nếu act không tồn tại → báo lỗi hoặc về dashboard
                die("Hành động '$act' không tồn tại trong $ctrlName.");
                // Hoặc redirect về dashboard:
                // header('Location: admin.php');
                // exit;
            }
        }
        exit();
    }
    // Nếu file controller không tồn tại
    // die("Controller $ctrlName không tồn tại.");
}

// Phần admin mặc định (giữ nguyên hoặc cải thiện thêm)
$controller = new AdminController();
$act = $_GET['act'] ?? 'home';

$allowedActions = [
    'home', 'product', 'add', 'edit', 'toggle_status', 'delete',
    'order', 'order_detail', 'update_order_status',
    'categories', 'category_add', 'category_edit', 'category_delete', 'category_toggle_status'
];

if (in_array($act, $allowedActions)) {
    if (in_array($act, ['category_edit', 'category_delete'])) {
        $id = $_GET['id'] ?? null;
        $controller->$act($id);
    } else {
        $controller->$act();
    }
} else {
    // Act không hợp lệ → về trang chủ admin
    $controller->page();
}
?>