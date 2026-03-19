<?php
// xét múi giờ
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Bắt đầu session ở đầu file
session_start();
ob_start();
// Debug session
if (isset($_GET['debug_session'])) {
    echo "<pre>SESSION: ";
    print_r($_SESSION);
    echo "GET: ";
    print_r($_GET);
    echo "</pre>";
    exit;
}
    include_once "./Views/user/layout_header.php";

if (isset($_GET['ctrl']) && isset($_GET['act'])) {
    $controllerFile = "./Controllers/" . ucfirst($_GET['ctrl']) . "Controller.php";
    
    if (file_exists($controllerFile)) {
        include_once $controllerFile;
        $controllerClass = ucfirst($_GET['ctrl']) . "Controller";
        
        if (class_exists($controllerClass)) {
            $ctrl = new $controllerClass();
            $act = $_GET['act'];
            
            if (method_exists($ctrl, $act)) {
                $args = array_slice($_GET, 2);
                $args = array_values($args);
                call_user_func_array([$ctrl, $act], $args);
            } else {
                echo "Phương thức $act không tồn tại";
            }
        } else {
            echo "Controller $controllerClass không tồn tại";
        }
    } else {
        echo "File controller không tồn tại: $controllerFile";
    }
} else {
    include_once "./Controllers/PageController.php";
    $ctrl = new PageController();
    $ctrl->home();
}

include_once "./Views/user/layout_footer.php";
// Bật hiển thị lỗi
error_reporting(E_ALL);
ini_set('display_errors', 1);

// xét múi giờ
date_default_timezone_set('Asia/Ho_Chi_Minh');
// ...
?>