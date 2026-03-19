<?php
include_once 'admin.php'; 
include_once './Models/Database.php';
include_once "./Models/Admin.php";
include_once "./Models/Order.php";
include_once './Models/Category.php';
include_once './Models/User.php';


class AdminController {
    
    private $model;
    private $conn;

    
    public function __construct() { $this->model = new Admin(); }

    // --- DASHBOARD ---

     function pageAdmin() {
        // Kiểm tra quyền
    }

    // --- HÀM DASHBOARD ĐÃ REFACTOR ---
    function page() {
        // 1. Khởi tạo model
        $productModel = new Admin();
        $orderModel   = new Order();

        // 2. Lấy dữ liệu thô từ Model (Không viết SQL ở đây nữa)
        $totalProducts       = $productModel->getTotalProducts();
        $ordersToday         = $orderModel->getOrdersToday();
        $monthlyRevenue      = $orderModel->getMonthlyRevenue();
        $revenueLast12Months = $orderModel->getRevenueLast12Months();
        
        // Gọi hàm mới vừa tạo bên Model
        $topProducts         = $productModel->getTopSellingProducts(10); 
        $orderStatusStats    = $orderModel->countByStatus();

        // 3. Xử lý Logic hiển thị (Màu sắc, Label) - Phần này giữ lại Controller là đúng
        $statusLabels = [];
        $statusData   = [];
        // Map màu sắc
        $statusColors = [
            'pending'    => '#ffc107',
            'confirmed'  => '#007bff',
            'shipping'   => '#17a2b8',
            'completed'  => '#28a745',
            'cancelled'  => '#dc3545',
            'returned'   => '#6c757d'
        ];

        // Format lại dữ liệu cho ChartJS
        foreach ($orderStatusStats as $row) {
            $st = $row['status'];
            $label = match($st) {
                'pending'    => 'Chờ xác nhận',
                'confirmed'  => 'Đã xác nhận',
                'shipping'   => 'Đang giao hàng',
                'completed'  => 'Hoàn thành',
                'cancelled'  => 'Đã hủy',
                'returned'   => 'Trả hàng',
                default      => ucfirst($st)
            };
            $statusLabels[] = $label;
            $statusData[]   = (int)$row['count'];
        }

        // 4. Gọi View
        include_once "./Views/admin/page_admin.php";
    }
    // --- DANH SÁCH SẢN PHẨM ---
    function product() {
        $categoryModel = new Category(); $categories = $categoryModel->getAll();
        $perPage = 10; $page = max(1, (int)($_GET['page'] ?? 1)); $offset = ($page - 1) * $perPage;
        $keyword = trim($_GET['keyword'] ?? ''); $cat_id = $_GET['cat_id'] ?? ''; $status = $_GET['status'] ?? '';
        
        $where = "WHERE 1=1"; $params = [];
        if ($keyword) { $where .= " AND p.name LIKE ?"; $params[] = "%$keyword%"; }
        if ($cat_id) { $where .= " AND p.category_id = ?"; $params[] = $cat_id; }
        if ($status === 'instock') $where .= " AND p.quantity > 10";
        if ($status === 'low') $where .= " AND p.quantity > 0 AND p.quantity <= 10";
        if ($status === 'out') $where .= " AND p.quantity = 0";

        $sql = "SELECT p.*, COALESCE(c.name, 'Chưa chọn') as cat_name, (SELECT MIN(price) FROM product_variants WHERE product_id = p.id) as display_price, (SELECT GROUP_CONCAT(CONCAT('<b>', color, ' - ', size, '</b>: ', stock) SEPARATOR '<br>') FROM product_variants WHERE product_id = p.id) as stock_detail FROM products p LEFT JOIN category c ON p.category_id = c.id $where ORDER BY p.id DESC LIMIT $perPage OFFSET $offset";
        $products = $this->model->query($sql, $params);
        $totalRow = $this->model->queryOne("SELECT COUNT(*) FROM products p $where", $params);
        $total = $totalRow['COUNT(*)'] ?? 0; $totalPages = max(1, ceil($total / $perPage));
        include_once "./Views/admin/page_product.php";
    }

    // --- ADD PRODUCT ---
    // --- ADD PRODUCT ---
    function add() {
        $categoryModel = new Category(); 
        $categories = $categoryModel->getAll();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST; 
            
            // Xử lý ảnh
            $image = $_FILES['image']; 
            $imageString = 'no-image.jpg';
            if ($image['size'] > 0) { 
                $target = "./Public/image/"; 
                if (!is_dir($target)) mkdir($target, 0777, true); 
                $fname = 'prod_' . time() . '_' . $image['name']; 
                move_uploaded_file($image['tmp_name'], $target . $fname); 
                $imageString = "image/" . $fname; 
            }
            
            $data['quantity'] = 0; 
            
            // Lấy màu mặc định từ Form
            $defaultColor = $_POST['default_color'] ?? 'Trắng';

            // Gọi hàm insert với tham số màu mới
            $newId = $this->model->insert($data, $imageString, $defaultColor);
            
            echo "<script>alert('Thêm mới thành công! Đã tạo biến thể màu $defaultColor. Hãy cập nhật giá và Size.'); window.location.href='admin.php?ctrl=admin&act=edit&id=$newId';</script>"; 
            exit;
        }
        include_once './Views/admin/admin_add.php';
    }
  // [MỚI] Hàm lấy ID vừa thêm vào (Khắc phục lỗi của bạn)
    public function lastInsertId() {
        return $this->conn->lastInsertId();
    }

    // --- ẨN/HIỆN STATUS ---
    function toggle_status() {
        if (isset($_GET['id']) && isset($_GET['status'])) {
            $this->model->updateStatus($_GET['id'], $_GET['status']);
            header('Location: admin.php?ctrl=admin&act=product'); exit();
        }
    }

    // --- EDIT PRODUCT (CLEAN) ---
   // --- EDIT PRODUCT (CLEAN) --

// File: AdminController.php

function edit() {
    if (!isset($_GET['id'])) { header('Location: admin.php?ctrl=admin&act=product'); exit(); }
    $id = $_GET['id'];
    
    $product = $this->model->getById($id);
    if (!$product) { echo "Sản phẩm không tồn tại"; return; }

    $cat = new Category();
    $categories = $cat->getAll();
    $variants = $this->model->getVariants($id);
    
    // [XÓA] Bỏ đoạn code tự động set remove=1 khi stock=0 để tránh ẩn nhầm
    // foreach ($variants as &$v) {
    //     if ($v['stock'] == 0) $v['remove'] = 1;
    // }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = $_POST;
        
        // Lấy danh sách biến thể hiện tại từ Form
        if(isset($_POST['variants'])) { 
            $variants = $_POST['variants']; 
        } elseif(isset($_POST['btn_save']) || isset($_POST['btn_add'])) { 
            $variants = []; 
        }

        // --- A. BẤM NÚT THÊM ---
        if (isset($_POST['btn_add'])) {
            $newVar = $_POST['new_variant'] ?? [];
            
            if (!empty($newVar['color']) && !empty($newVar['size'])) {
                $colorInput = trim($newVar['color']);
                $sizeInput  = trim($newVar['size']);

                // Check trùng lặp... (giữ nguyên logic check của bạn)
                $isDuplicate = false;
                foreach ($variants as $existing) {
                    if (strcasecmp($existing['color'], $colorInput) == 0 && 
                        strcasecmp($existing['size'], $sizeInput) == 0) {
                        $isDuplicate = true; break;
                    }
                }

                if ($isDuplicate) {
                    echo "<script>alert('LỖI: Biến thể đã tồn tại!');</script>";
                } else {
                    $newImgPath = '';
                    if (isset($_FILES['new_variant_image']) && $_FILES['new_variant_image']['size'] > 0) {
                        // ... (giữ nguyên logic upload ảnh) ...
                        $target = "./Public/image/"; 
                        if (!is_dir($target)) mkdir($target, 0777, true);
                        $fname = 'var_' . time() . '_' . $_FILES['new_variant_image']['name'];
                        move_uploaded_file($_FILES['new_variant_image']['tmp_name'], $target . $fname);
                        $newImgPath = "image/" . $fname;
                    }

                    $variants[] = [
                        'color' => $colorInput, 
                        'size'  => $sizeInput, 
                        'price' => (int)$newVar['price'], 
                        'stock' => (int)$newVar['stock'], 
                        'image' => $newImgPath, 
                        'status'=> 'show' // [MỚI] Mặc định là hiện
                    ];
                }
            } else {
                 echo "<script>alert('Vui lòng nhập đầy đủ Màu sắc và Size!');</script>";
            }
            // Giữ lại data form
            $product['name'] = $_POST['name']; 
            $product['description'] = $_POST['description']; 
            $product['category_id'] = $_POST['category_id'];
        }

        // --- B. BẤM ẨN (Cập nhật status = hide) ---
        if (isset($_POST['btn_hide'])) {
            $index = $_POST['btn_hide']; 
            if (isset($variants[$index])) $variants[$index]['status'] = 'hide'; // [SỬA] Thay remove bằng status
            
            // Giữ lại data form
            $product['name'] = $_POST['name']; $product['description'] = $_POST['description']; $product['category_id'] = $_POST['category_id'];
        }
        
        // --- C. BẤM HIỆN (Cập nhật status = show) ---
        if (isset($_POST['btn_unhide'])) {
            $index = $_POST['btn_unhide']; 
            if (isset($variants[$index])) $variants[$index]['status'] = 'show'; // [SỬA] Thay remove bằng status
            
            // Giữ lại data form
            $product['name'] = $_POST['name']; $product['description'] = $_POST['description']; $product['category_id'] = $_POST['category_id'];
        }

        // --- D. BẤM LƯU ---
        if (isset($_POST['btn_save'])) {
            
            // 1. TÍNH NĂNG THÔNG MINH (Auto Add)
            $tempVar = $_POST['new_variant'] ?? [];
            if (!empty($tempVar['color']) && !empty($tempVar['size'])) {
                 // ... (giữ nguyên logic check trùng & upload ảnh) ...
                 // (Giả sử đoạn logic này bạn giữ nguyên như cũ, chỉ sửa đoạn thêm mảng bên dưới)
                 
                 // Khi thêm tự động, set status = show
                 if (!$isDuplicate) {
                     // ... logic upload ảnh tempImgPath ...
                     $variants[] = [
                        // ... các trường khác ...
                        'status'=> 'show' // [MỚI]
                     ];
                 }
            }

            // 2. Lưu ảnh chính & 3. Lưu ảnh biến thể (Giữ nguyên)
            // ...

            // 4. Gọi Model Lưu
            $this->model->update($id, $data, $imageString ?? '');
            
            // QUAN TRỌNG: Đảm bảo Model->saveVariants() lưu cột status vào DB
            $this->model->saveVariants($id, $variants);
            
            echo "<script>alert('Cập nhật thành công!'); window.location.href='admin.php?ctrl=admin&act=product';</script>"; 
            exit;
        }
    }
    
    include './Views/admin/admin_edit.php';
}
    // --- DELETE (Cũ, ít dùng vì đã có ẩn/hiện) ---
    function delete() {
        if (isset($_GET['id'])) { $this->model->delete($_GET['id']); header('Location: admin.php?ctrl=admin&act=product'); exit(); }
    }
    
    // --- ORDER ---
    function order() {
        $orderModel = new Order(); $keyword = trim($_GET['keyword'] ?? ''); $status = $_GET['status'] ?? ''; $page = max(1, (int)($_GET['page'] ?? 1)); $perPage = 10;
        $orders = $orderModel->getAllOrders($keyword, $status, $perPage, ($page - 1) * $perPage);
        $totalOrders = $orderModel->countOrders($keyword, $status); $totalPages = max(1, ceil($totalOrders / $perPage));
        include_once "./Views/admin/page_order.php";
    }
    function order_detail() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            
            $orderModel = new Order();
            
            // Lấy thông tin đơn hàng
            $order = $orderModel->getOrderById($id);
            
            // Lấy danh sách sản phẩm trong đơn
            $items = $orderModel->getOrderItems($id); 
            
            // Include View
            include_once "Views/admin/page_order_detail.php";
        } else {
            // Nếu không có ID thì quay về danh sách
            header("Location: ?ctrl=admin&act=order");
        }
    }

    // --- CẬP NHẬT TRẠNG THÁI (ĐÃ SỬA) ---
    public function update_order_status() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $id = $_POST['id'];
            $newStatus = $_POST['status'];
            $payment_status = $_POST['payment_status']; 

            $orderModel = new Order();
            
            $currentOrder = $orderModel->getOrderById($id);
            $currentStatus = $currentOrder['status'];

            $allowedTransitions = [
                'pending'   => ['confirmed', 'cancelled'],    
                'confirmed' => ['shipping', 'cancelled'],     
                'shipping'  => ['completed'],               
                'completed' => [],                            
                'cancelled' => []                    
            ];

            if ($newStatus !== $currentStatus && !in_array($newStatus, $allowedTransitions[$currentStatus])) {
                echo "<script>alert('Không thể chuyển từ trạng thái [$currentStatus] sang [$newStatus] theo quy trình!'); window.history.back();</script>";
                exit;
            }

            if ($newStatus === 'completed') {
                $payment_status = 'paid';
            }
            $orderModel->updateOrderFullStatus($id, $newStatus, $payment_status);

            header("Location: ?ctrl=admin&act=order_detail&id=$id");
            exit;
        }
        
        header("Location: ?ctrl=admin&act=order");
        exit;
    }

    // danh muc
        public function category(){
        include_once './Views/admin/page_categories.php';
    }
    public function categories()
{
    $categoryModel = new Category();
    $allCategories = $categoryModel->getAll();

    // Tìm kiếm và lọc
    $keyword = trim($_GET['keyword'] ?? '');
    $status_filter = $_GET['status'] ?? ''; // '' hoặc '0' hoặc '1'

    $filtered = $allCategories;

    if ($keyword !== '') {
        $filtered = array_filter($filtered, function($cat) use ($keyword) {
            return stripos($cat['name'], $keyword) !== false;
        });
    }

    if ($status_filter !== '') {
        $filtered = array_filter($filtered, function($cat) use ($status_filter) {
            return $cat['status'] == $status_filter;
        });
    }

    // Phân trang
    $perPage = 20;
    $page = max(1, (int)($_GET['page'] ?? 1));
    $total = count($filtered);
    $offset = ($page - 1) * $perPage;
    $displayCategories = array_slice($filtered, $offset, $perPage);
    $totalPages = max(1, ceil($total / $perPage));

    include_once './Views/admin/page_categories.php';
}

// Thêm danh mục
public function category_add()
{
    $categoryModel = new Category();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'name'       => trim($_POST['name']),
            'parent_id'  => $_POST['parent_id'] ?: null,
            'status'     => $_POST['status'] ?? 1
        ];
        $categoryModel->insert($data);
        header('Location: admin.php?ctrl=admin&act=categories');
        exit;
    }

    $allCategories = $categoryModel->getAll();
    include_once './Views/admin/category_add.php';
}

// Sửa danh mục
public function category_edit($id = null)
{
    if (!$id) {
        header('Location: admin.php?ctrl=admin&act=categories');
        exit;
    }

    $categoryModel = new Category();
    $cat = $categoryModel->getById($id);

    if (!$cat) {
        header('Location: admin.php?ctrl=admin&act=categories');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'name'       => trim($_POST['name']),
            'parent_id'  => $_POST['parent_id'] ?: null,
            'status'     => $_POST['status'] ?? 1
        ];
        $categoryModel->update($id, $data);
        header('Location: admin.php?ctrl=admin&act=categories');
        exit;
    }

    $allCategories = $categoryModel->getAll();
    include_once './Views/admin/category_edit.php';
}

// Xóa danh mục
public function category_delete($id = null)
{
    if ($id) {
        (new Category())->delete($id);
    }
    header('Location: admin.php?ctrl=admin&act=categories');
    exit;
}

// AJAX toggle status
public function category_toggle_status()
{
    if (isset($_POST['id'])) {
        (new Category())->toggleStatus($_POST['id']);
        echo json_encode(['success' => true]);
        exit;
    }
    echo json_encode(['success' => false]);
    exit;
}
// --- THÊM VÀO CUỐI AdminController.php ---

    // Chức năng Khóa/Mở khóa nhanh tài khoản (cho nút bấm ngoài danh sách)
    public function user_toggle_status()
    {
        // Kiểm tra ID có tồn tại không
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $userModel = new User();
            
            // Không cho phép khóa chính mình
            if (isset($_SESSION['user']) && $_SESSION['user']['id'] == $id) {
                $_SESSION['error'] = "Không thể tự khóa tài khoản của chính mình!";
            } else {
                // Gọi hàm toggleBanStatus trong Model User
                $userModel->toggleBanStatus($id);
                $_SESSION['success'] = "Đã cập nhật trạng thái tài khoản thành công!";
            }
        }
        // Quay lại trang danh sách (act=users)
        header('Location: admin.php?ctrl=admin&act=users');
        exit;
    }
}
?>