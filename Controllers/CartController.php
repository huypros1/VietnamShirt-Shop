<?php
include_once "./Models/Product.php";

class CartController
{
    private $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
    }

    public function index()
    {
        $cartItems = [];
        $totalPrice = 0;
        $totalItems = 0;

        // Xử lý logic đường dẫn ảnh để tránh lỗi hiển thị
        function fixImgPath($path) {
            if (empty($path)) return "default.jpg";
            // Nếu trong DB đã lưu "image/...", bỏ bớt prefix nếu cần hoặc giữ nguyên tùy cấu trúc thư mục
            return $path; 
        }

        if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $key => $quantity) {
                $parts = explode('_', $key);
                // Đảm bảo key hợp lệ
                if (count($parts) < 2) continue;

                $pro_id = $parts[0];
                $pro_size = $parts[1];
                $pro_color = $parts[2] ?? 'Trắng';

                $product = $this->productModel->getById($pro_id);
                $variant = $this->productModel->getVariant($pro_id, $pro_size, $pro_color);

                if ($product && $variant) {
                    // LOGIC ẢNH: Ưu tiên ảnh biến thể, nếu không có lấy ảnh sản phẩm cha
                    $final_image = !empty($variant['image']) ? $variant['image'] : $product['image'];

                    $item = [
                        'key'      => $key,         
                        'id'       => $product['id'],
                        'name'     => $product['name'],
                        'image'    => fixImgPath($final_image), // Sử dụng hàm xử lý ảnh
                        'size'     => $pro_size,
                        'color'    => $pro_color,
                        'price'    => $variant['price'], 
                        'qty'      => $quantity,
                        'subtotal' => $variant['price'] * $quantity,
                        'max_stock'=> $variant['stock'] // Thêm tồn kho để giới hạn input
                    ];
                    
                    $cartItems[] = $item;
                    $totalPrice += $item['subtotal'];
                    $totalItems += $quantity;
                }
            }
        }
        
        $shippingFee = 30000;
        // Nếu giỏ trống hoặc tổng tiền quá lớn (ví dụ > 500k) có thể freeship tùy chính sách
        include_once "./Views/user/cart.php";
    }

    // Xử lý Thêm vào giỏ / Mua ngay
public function add()
    {
        if (isset($_GET['id'])) {
            $pro_id = (int)$_GET['id'];
            $size = $_POST['size'] ?? '';
            $color = $_POST['color'] ?? '';
            $qty = isset($_POST['quantity']) ? (int)$_POST['quantity'] : (isset($_POST['sl']) ? (int)$_POST['sl'] : 1);
            
            // 1. Validate dữ liệu đầu vào
            if(empty($size) || empty($color)) {
                $_SESSION['error'] = "Vui lòng chọn Size và Màu sắc!";
                // Quay lại trang cũ
                header("Location: " . $_SERVER['HTTP_REFERER']); 
                exit;
            }

            // 2. Kiểm tra tồn kho từ Database
            $variant = $this->productModel->getVariant($pro_id, $size, $color);
            if (isset($_POST['buy_now'])) {
                    if ($variant['stock'] < $qty) {
                        $_SESSION['error'] = "Kho chỉ còn " . $variant['stock'] . " sản phẩm, không đủ để mua ngay!";
                        header("Location: " . $_SERVER['HTTP_REFERER']);
                        exit;
                    }

                    $_SESSION['direct_buy'] = [
                        'product_id' => $pro_id,
                        'size'       => $size,
                        'color'      => $color,
                        'quantity'   => $qty
                    ];
                    header("Location: ?ctrl=order&act=checkout");
                    exit;
                }

                // NẾU LÀ THÊM VÀO GIỎ: Mới cần cộng dồn để check
                if ($variant['stock'] < $qty) {
                    $_SESSION['error'] = "Kho chỉ còn " . $variant['stock'] . " sản phẩm!";
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                    exit;
                }

            // --- TRƯỜNG HỢP B: THÊM VÀO GIỎ HÀNG ---
            
            // Xóa session mua ngay nếu có (để tránh xung đột lần sau)
            if(isset($_SESSION['direct_buy'])) unset($_SESSION['direct_buy']);

            $key = $pro_id . '_' . $size . '_' . $color;
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            
            // Cộng dồn số lượng nếu đã có trong giỏ
            $currentQty = isset($_SESSION['cart'][$key]) ? $_SESSION['cart'][$key] : 0;
            
            // Kiểm tra tổng số lượng có vượt kho không
            if (($currentQty + $qty) > $variant['stock']) {
                $_SESSION['error'] = "Bạn đã có $currentQty trong giỏ. Không thể thêm quá số lượng tồn kho!";
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit;
            }

            if (isset($_SESSION['cart'][$key])) {
                $_SESSION['cart'][$key] += $qty;
            } else {
                $_SESSION['cart'][$key] = $qty;
            }
            
            $_SESSION['success'] = "Đã thêm vào giỏ hàng thành công!";
            
            // --- QUAN TRỌNG: Ở NGUYÊN TRANG (Quay lại trang chi tiết sản phẩm) ---
            // Dùng HTTP_REFERER để quay lại đúng trang vừa gửi request
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        }
        
        header("Location: ?ctrl=page&act=home");
        exit;
    }

    public function increase()
    {
        if (isset($_GET['id'])) {
            $key = $_GET['id'];
            if (isset($_SESSION['cart'][$key])) {
                // Kiểm tra tồn kho trước khi tăng
                $parts = explode('_', $key);
                $variant = $this->productModel->getVariant($parts[0], $parts[1], $parts[2]);
                
                if ($variant && $_SESSION['cart'][$key] < $variant['stock']) {
                    $_SESSION['cart'][$key]++;
                } else {
                    $_SESSION['error'] = "Đã đạt giới hạn tồn kho!";
                }
            }
        }
        header("Location: ?ctrl=cart&act=index");
        exit;
    }

    public function decrease()
    {
        if (isset($_GET['id'])) {
            $key = $_GET['id'];
            if (isset($_SESSION['cart'][$key])) {
                $_SESSION['cart'][$key]--;
                if ($_SESSION['cart'][$key] <= 0) {
                    unset($_SESSION['cart'][$key]);
                }
            }
        }
        header("Location: ?ctrl=cart&act=index");
        exit;
    }

    public function remove()
    {
        if (isset($_GET['id'])) {
            $key = $_GET['id'];
            if (isset($_SESSION['cart'][$key])) {
                unset($_SESSION['cart'][$key]);
                $_SESSION['success'] = "Đã xóa sản phẩm!";
            }
        }
        header("Location: ?ctrl=cart&act=index");
        exit;
    }

    public function clear()
    {
        $_SESSION['cart'] = [];
        $_SESSION['success'] = "Đã làm trống giỏ hàng!";
        header("Location: ?ctrl=cart&act=index");
        exit;
    }
}
?>