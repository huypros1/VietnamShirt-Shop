<?php
include_once "./Models/Product.php";
include_once "./Models/Database.php";
include_once "./Models/Order.php";

class OrderController
{
    private $db;
    private $productModel;
    
    
    public function __construct()
    {
        $this->db = new Database();
        $this->productModel = new Product();
    }

    // Trang checkout
public function checkout()
    {
        if (empty($_SESSION) || !isset($_SESSION['user'])) {
            $_SESSION['error_login'] = "Vui lòng đăng nhập để thanh toán!";
            header("Location: ?ctrl=user&act=login"); exit;
        }

        $items = [];
        $total = 0;

        // KIỂM TRA: NẾU CÓ SESSION 'DIRECT_BUY' THÌ ƯU TIÊN XỬ LÝ NÓ (CHẾ ĐỘ MUA NGAY)
        if (isset($_SESSION['direct_buy'])) {
            $data = $_SESSION['direct_buy'];
            
            $p = $this->productModel->getById($data['product_id']);
            $v = $this->productModel->getVariant($data['product_id'], $data['size'], $data['color']);

            if ($p && $v) {
                $subtotal = $v['price'] * $data['quantity'];
                $total += $subtotal;
                $items[] = [
                    'product'  => $p,
                    'variant'  => $v,
                    'size'     => $data['size'],
                    'color'    => $data['color'],
                    'quantity' => $data['quantity'],
                    'subtotal' => $subtotal
                ];
            }
        } 
        // NẾU KHÔNG CÓ DIRECT_BUY, LẤY TỪ GIỎ HÀNG THƯỜNG
        elseif (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $key => $qty) {
                $parts = explode('_', $key);
                // Hỗ trợ cả key cũ (2 phần) và key mới (3 phần)
                $pro_id = $parts[0];
                $pro_size = $parts[1];
                $pro_color = $parts[2] ?? 'Trắng'; 

                $p = $this->productModel->getById($pro_id);
                $v = $this->productModel->getVariant($pro_id, $pro_size, $pro_color);

                if ($p && $v) {
                    $subtotal = $v['price'] * $qty;
                    $total += $subtotal;
                    $items[] = [
                        'product'  => $p,
                        'variant'  => $v,
                        'size'     => $pro_size,
                        'color'    => $pro_color,
                        'quantity' => $qty,
                        'subtotal' => $subtotal
                    ];
                }
            }
        } else {
            // Không có gì để thanh toán
            header("Location: ?ctrl=page&act=home"); exit;
        }

        $shipping_fee = 30000;
        $final_total = $total + $shipping_fee;

        include_once "./Views/user/order_checkout.php";
    }

    // 2. XỬ LÝ ĐẶT HÀNG
public function placeOrder()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: ?ctrl=page&act=home"); exit;
        }

        // Kiểm tra xem nguồn hàng từ đâu (Mua ngay hay Giỏ hàng)
        $is_direct_buy = isset($_SESSION['direct_buy']);
        $cart_source = $is_direct_buy ? [$_SESSION['direct_buy']] : ($_SESSION['cart'] ?? []);

        if (empty($cart_source) && !$is_direct_buy) {
             header("Location: ?ctrl=page&act=home"); exit;
        }

        // Lấy thông tin form
        $name    = $_SESSION['user']['name'];
        $email   = $_SESSION['user']['email'] ?? '';
        $phone   = trim($_POST['phone']);
        $address = trim($_POST['address']);
        $note    = trim($_POST['note'] ?? '');

        if (empty($phone) || empty($address)) {
            $_SESSION['error'] = "Vui lòng nhập thông tin!";
            header("Location: ?ctrl=order&act=checkout"); exit;
        }

        // TÍNH LẠI TỔNG TIỀN (Backend Validation)
        $total = 0;
        if ($is_direct_buy) {
            $d = $_SESSION['direct_buy'];
            $v = $this->productModel->getVariant($d['product_id'], $d['size'], $d['color']);
            if($v) $total = $v['price'] * $d['quantity'];
        } else {
            foreach ($_SESSION['cart'] as $key => $qty) {
                $parts = explode('_', $key);
                $color = $parts[2] ?? 'Trắng';
                $v = $this->productModel->getVariant($parts[0], $parts[1], $color);
                if($v) $total += $v['price'] * $qty;
            }
        }

        $shipping_fee = 30000;
        $total_amount = $total + $shipping_fee;

        // INSERT ORDER
        $sql = "INSERT INTO orders (user_id, name, email, phone, address, note, total_amount, Shipping_fee, pay_method_id, payment_status, status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, 'unpaid', 'pending', NOW())";
        $order_id = $this->db->insert($sql, $_SESSION['user']['id'], $name, $email, $phone, $address, $note, $total_amount, $shipping_fee);

        if ($order_id) {
            // --- SỬA LẠI PHẦN INSERT ORDER ITEM ĐỂ CÓ PRODUCT_ID ---
            
            if ($is_direct_buy) {
                // 1. TRƯỜNG HỢP MUA NGAY
                $d = $_SESSION['direct_buy'];
                $variant = $this->productModel->getVariant($d['product_id'], $d['size'], $d['color']);
                
                if ($variant) {
                    // Thêm cột product_id vào câu SQL
                    $sql_item = "INSERT INTO order_item (order_id, product_id, variant_id, quantity, price) VALUES (?, ?, ?, ?, ?)";
                    
                    // Truyền thêm $variant['product_id'] vào tham số
                    $this->db->insert($sql_item, 
                                      $order_id, 
                                      $variant['product_id'], // Lấy product_id từ biến thể
                                      $variant['id'], 
                                      $d['quantity'], 
                                      $variant['price']);
                    
                    // Trừ kho
                    $this->productModel->decreaseVariantStock($variant['id'], $d['quantity']);
                }
                unset($_SESSION['direct_buy']);

            } else {
                // 2. TRƯỜNG HỢP MUA TỪ GIỎ HÀNG
                foreach ($_SESSION['cart'] as $key => $quantity) {
                    $parts = explode('_', $key);
                    $pro_id = $parts[0];
                    $pro_size = $parts[1];
                    $pro_color = $parts[2] ?? 'Trắng';
                    
                    $variant = $this->productModel->getVariant($pro_id, $pro_size, $pro_color);
                    
                    if ($variant) {
                        // Thêm cột product_id vào câu SQL
                        $sql_item = "INSERT INTO order_item (order_id, product_id, variant_id, quantity, price) VALUES (?, ?, ?, ?, ?)";
                        
                        // Truyền thêm $variant['product_id'] vào tham số
                        $this->db->insert($sql_item, 
                                          $order_id, 
                                          $variant['product_id'], // Lấy product_id từ biến thể
                                          $variant['id'], 
                                          $quantity, 
                                          $variant['price']);
                         
                        // Trừ kho
                        $this->productModel->decreaseVariantStock($variant['id'], $quantity);
                    }
                }
                unset($_SESSION['cart']);
            }

            $_SESSION['success'] = "Đặt hàng thành công! Mã đơn: #$order_id";
            header("Location: ?ctrl=order&act=success&id=$order_id");
            exit;
        } else {
            $_SESSION['error'] = "Đặt hàng thất bại!";
            header("Location: ?ctrl=order&act=checkout");
            exit;
        }
    }
public function detail() {
    if (!isset($_GET['id'])) {
        header("Location: ?ctrl=user&act=myOrders");
        exit;
    }
    
    $id = $_GET['id'];
    $user_id = $_SESSION['user']['id'] ?? 0; 
    
    $orderModel = new Order(); 
    $order = $orderModel->getOrderById($id);

    // 1. Kiểm tra quyền sở hữu đơn hàng
    if (!$order || $order['user_id'] != $user_id) {
        $_SESSION['error'] = "Bạn không có quyền xem đơn hàng này.";
        header("Location: ?ctrl=user&act=myOrders");
        exit;
    }

    $items = $orderModel->getOrderItems($id);

    // 2. XỬ LÝ FORM ĐÁNH GIÁ (ĐÃ SỬA LẠI LOGIC)
    $message_success = "";
    $message_error = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
            if ($order['status'] === 'completed') {
            
            // Lấy dữ liệu từ form
                $product_id = $_POST['product_id_review'] ?? null;
                $rating     = $_POST['rating'] ?? 5; // Lấy giá trị từ radio button (1-5)
                $content    = trim($_POST['content'] ?? '');

            if ($product_id && !empty($rating)) {
                // Kiểm tra xem sản phẩm này có thực sự nằm trong đơn hàng không (tránh hack form)
                $isValidProduct = false;
                foreach ($items as $item) {
                    if (($item['product_id'] ?? $item['id']) == $product_id) {
                        $isValidProduct = true;
                        break;
                    }
                }

                if ($isValidProduct) {
                    // Gọi model Product để lưu đánh giá
                    // $this->productModel đã được khởi tạo ở __construct
                    $this->productModel->insertComment($product_id, $user_id, $content, $rating);
                    
                    $message_success = "Đánh giá sản phẩm thành công! Cảm ơn bạn.";
                    
                    // (Tùy chọn) Reset lại biến để tránh resubmit khi F5, hoặc redirect
                    // header("Location: ?ctrl=order&act=detail&id=$id"); exit; 
                } else {
                    $message_error = "Sản phẩm không hợp lệ trong đơn hàng này.";
                }
            } else {
                $message_error = "Vui lòng chọn sản phẩm và mức đánh giá.";
            }

        } else {
            $message_error = "Đơn hàng chưa hoàn thành, không thể đánh giá.";
        }
    }

    // Include View
    include_once "./Views/user/order_detail.php";
}

    // // lịch sử đơn hàng
    // function getHistory()
    // {
    // $user_id = $_SESSION['user_id'];
    // $order = $this->model->getHistory($user_id);
    // include_once "./Views/";
    // }
    public function cancel() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header("Location: ?ctrl=user&act=login"); exit;
        }

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $user_id = $_SESSION['user']['id'];
            $orderModel = new Order();

            // Lấy thông tin đơn hàng để kiểm tra quyền sở hữu và trạng thái
            $order = $orderModel->getOrderById($id);

            // Validate: Đúng chủ đơn hàng VÀ Trạng thái phải là 'pending'
            if ($order && $order['user_id'] == $user_id && $order['status'] == 'pending') {
                
                // Thực hiện hủy (Cập nhật status = cancelled)
                // Lưu ý: Nếu trong Model chưa có hàm riêng, dùng updateStatus hoặc viết query trực tiếp
                // Ở đây giả định dùng hàm cancelOrder (sẽ viết ở phần Model bên dưới)
                $orderModel->cancelOrder($id);

                $_SESSION['success'] = "Đã hủy đơn hàng thành công!";
            } else {
                $_SESSION['error'] = "Không thể hủy đơn hàng này (Đã được xử lý hoặc không tồn tại).";
            }
        }
        
        // Quay lại trang danh sách đơn hàng
        header("Location: ?ctrl=user&act=myOrders");
        exit;
    }

    // --- 2. HÀM CHUYỂN ĐẾN TRANG ĐÁNH GIÁ ---
    public function review() {
        if (!isset($_SESSION['user'])) {
            header("Location: ?ctrl=user&act=login"); exit;
        }

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $user_id = $_SESSION['user']['id'];
            $orderModel = new Order();

            $order = $orderModel->getOrderById($id);

            // Chỉ cho phép đánh giá khi đơn hàng đã hoàn thành
            if ($order && $order['user_id'] == $user_id && $order['status'] == 'completed') {
                
                // Lấy danh sách sản phẩm để hiển thị ở trang đánh giá
                $items = $orderModel->getOrderItems($id);
                
                // Logic xử lý Submit Form đánh giá (Nếu bạn submit tại trang này)
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
                    // Code xử lý lưu đánh giá vào database ở đây
                    // ...
                    echo "<script>alert('Cảm ơn bạn đã đánh giá!');</script>";
                }

                // Load View đánh giá (File order_cmt.php bạn đã gửi)
                include_once "./Views/user/order_cmt.php"; 
                
            } else {
                $_SESSION['error'] = "Đơn hàng chưa hoàn thành, chưa thể đánh giá.";
                header("Location: ?ctrl=user&act=myOrders");
                exit;
            }
        } else {
            header("Location: ?ctrl=user&act=myOrders");
            exit;
        }
    }
    // File: OrderController.php

    public function buyAgain() {
        // 1. Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header("Location: ?ctrl=user&act=login"); exit;
        }

        // 2. Kiểm tra có ID đơn hàng không
        if (isset($_GET['id'])) {
            $order_id = $_GET['id'];
            $orderModel = new Order();

            // Lấy danh sách sản phẩm của đơn hàng cũ
            $items = $orderModel->getOrderItems($order_id);

            if ($items) {
                // Khởi tạo giỏ hàng nếu chưa có
                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }

                foreach ($items as $item) {
                    // Cấu trúc logic giống CartController: id_size_color
                    // Lưu ý: Đảm bảo bảng order_item của bạn có cột product_id, size, color
                    
                    $pro_id = $item['product_id']; 
                    $size = $item['size'];
                    // Nếu không có màu thì mặc định là Trắng (cho khớp logic Cart)
                    $color = !empty($item['color']) ? $item['color'] : 'Trắng'; 
                    $qty = $item['quantity'];

                    // Tạo key cho giỏ hàng
                    $key = $pro_id . '_' . $size . '_' . $color;

                    // Cộng dồn số lượng vào session giỏ hàng
                    if (isset($_SESSION['cart'][$key])) {
                        $_SESSION['cart'][$key] += $qty;
                    } else {
                        $_SESSION['cart'][$key] = $qty;
                    }
                }

                $_SESSION['success'] = "Đã thêm sản phẩm từ đơn cũ vào giỏ hàng!";
                
                // 3. CHUYỂN HƯỚNG VỀ GIỎ HÀNG (Quan trọng)
                header("Location: ?ctrl=cart&act=index");
                exit;
            } else {
                $_SESSION['error'] = "Không tìm thấy sản phẩm trong đơn hàng này.";
            }
        }
        
        // Nếu lỗi thì quay về danh sách đơn
        header("Location: ?ctrl=user&act=myOrders");
        exit;
    }
    public function success() {
        // Kiểm tra xem có ID đơn hàng trên URL không
        if (isset($_GET['id'])) {
            $order_id = $_GET['id'];
            
            // (Tùy chọn) Có thể gọi Model để lấy thông tin đơn hàng nếu muốn hiển thị chi tiết hơn
            // $orderModel = new Order();
            // $order = $orderModel->getOrderById($order_id);

            // Gọi View hiển thị thông báo thành công
            include_once "./Views/user/order_success.php";
        } else {
            // Nếu không có ID, quay về trang chủ
            header("Location: ?ctrl=page&act=home");
            exit;
        }
    }
}
?>