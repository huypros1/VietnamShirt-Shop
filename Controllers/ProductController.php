<?php
include_once "Models/Product.php";
include_once "Models/Category.php";

class ProductController {
    private $model;

    public function __construct() {
        $this->model = new Product();
    }

    // --- CHI TIẾT SẢN PHẨM (ĐÃ SỬA LOGIC ẢNH) ---
   // --- HÀM DETAIL (ĐẦY ĐỦ LOGIC MỚI) ---
    function detail($id) {
        $product = $this->model->getById($id);
        if (!$product) { echo "Sản phẩm không tồn tại"; return; }

        $variants = $this->model->getVariants($id);
        if (empty($variants)) $variants = [['size'=>'S', 'color'=>'Trắng', 'price'=>0, 'stock'=>0, 'image'=>'']];

        // --- XỬ LÝ SIZE & MÀU (Như cũ) ---
        $thu_tu_size = ['S','M','L','XL','XXL','3XL','FreeSize'];
        $ds_size = []; foreach ($variants as $v) { $ds_size[] = $v['size']; }
        $ds_size = array_unique($ds_size);
        usort($ds_size, function($a, $b) use ($thu_tu_size) {
            $posA = array_search($a, $thu_tu_size); $posB = array_search($b, $thu_tu_size);
            return ($posA === false ? 99 : $posA) - ($posB === false ? 99 : $posB);
        });
        $ds_mau = []; foreach ($variants as $v) { if (!in_array($v['color'], $ds_mau)) $ds_mau[] = $v['color']; }
        $size = $_POST['size'] ?? ($_POST['old_size'] ?? ($ds_size[0] ?? ''));
        $color = $_POST['color'] ?? ($_POST['old_color'] ?? ($ds_mau[0] ?? ''));

        // --- XỬ LÝ ẢNH ---
        $all_images = [];
        $all_images[] = ['image_path' => $product['image'], 'color_ref' => 'All']; 
        foreach ($variants as $v) {
            if (!empty($v['image'])) {
                $exists = false; foreach ($all_images as $img) { if ($img['image_path'] == $v['image']) { $exists = true; break; } }
                if (!$exists) $all_images[] = ['image_path' => $v['image'], 'color_ref' => $v['color']];
            }
        }
        $current_main_image = $product['image'];
        if (isset($_POST['chon_anh'])) { $current_main_image = $_POST['chon_anh']; foreach ($variants as $v) { if ($v['image'] == $current_main_image) { $color = $v['color']; break; } } } 
        elseif (isset($_POST['color']) || isset($_POST['old_color'])) { foreach ($variants as $v) { if ($v['color'] == $color && !empty($v['image'])) { $current_main_image = $v['image']; break; } } }

        // --- GIÁ & KHO ---
        $gia = 0; $stock = 0; $found = false;
        foreach ($variants as $v) { if ($v['size'] == $size && $v['color'] == $color) { $gia = $v['price']; $stock = $v['stock']; $found = true; break; } }
        if (!$found) foreach ($variants as $v) { if ($v['size'] == $size) { $gia = $v['price']; break; } }
        $sl = max(1, (int)($_POST['sl'] ?? 1)); if (isset($_POST['tang'])) $sl++; if (isset($_POST['giam'])) $sl--; if ($stock > 0 && $sl > $stock) $sl = $stock;

        // --- DỮ LIỆU PHỤ ---
        $isFavorite = isset($_SESSION['user']) ? $this->model->checkFavorite($_SESSION['user']['id'], $id) : false;
        $list_comments = $this->model->getComments($id);
        $list_questions = $this->model->getQuestions($id); // Lấy câu hỏi cha
        $related_products = $this->model->getRelatedProducts($product['category_id'], $id);
        $bang_size = $this->model->getSizeGuide();

        // --- [QUAN TRỌNG] XỬ LÝ GỬI CÂU HỎI & TRẢ LỜI ---
        
        // 1. Khách đặt câu hỏi mới
        if (isset($_POST['submit_question']) && isset($_SESSION['user'])) {
            $this->model->addQuestion($_SESSION['user']['id'], $id, $_POST['question_content']);
            echo "<script>window.location.href='?ctrl=product&act=detail&id=$id';</script>"; exit;
        }

        // 2. [MỚI] Khách/Admin trả lời vào câu hỏi có sẵn (Reply)
        if (isset($_POST['submit_user_reply']) && isset($_SESSION['user'])) {
            $parent_id = $_POST['parent_id'];
            $content = $_POST['user_reply_content'];
            $this->model->addQuestion($_SESSION['user']['id'], $id, $content, $parent_id);
            echo "<script>window.location.href='?ctrl=product&act=detail&id=$id';</script>"; exit;
        }

        include_once "./Views/user/product_detail.php";
    }

    // --- CÁC HÀM KHÁC GIỮ NGUYÊN ---
    // --- HÀM TOGGLE FAVORITE (FIX LỖI XÓA 1 RA 2) ---
    function toggle_favorite() {
        if (!isset($_SESSION['user'])) { 
            $_SESSION['swal'] = ['type' => 'warning', 'title' => 'Chưa đăng nhập', 'text' => 'Vui lòng đăng nhập.'];
            header("Location: ?ctrl=user&act=login"); exit; 
        }
        
        $id = $_GET['id'];
        // Trim để xóa khoảng trắng thừa, tránh lỗi so sánh chuỗi
        $size = trim($_POST['size'] ?? ($_GET['size'] ?? ''));
        $color = trim($_POST['color'] ?? ($_GET['color'] ?? ''));

        // Gọi Model xử lý
        $status = $this->model->toggleFavorite($_SESSION['user']['id'], $id, $size, $color);
        
        if ($status == 'added') {
            $_SESSION['swal'] = ['type' => 'success', 'title' => 'Đã yêu thích', 'text' => "Đã lưu ($size - $color)"];
        } else {
            $_SESSION['swal'] = ['type' => 'info', 'title' => 'Đã hủy', 'text' => 'Đã xóa khỏi danh sách.'];
        }
        
        // Redirect về trang cũ
        if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'act=favorite_list') !== false) {
             header("Location: ?ctrl=product&act=favorite_list");
        } else {
             header("Location: ?ctrl=product&act=detail&id=$id&size=$size&color=$color"); 
        }
        exit;
    }

    function favorite_list() { if (!isset($_SESSION['user'])) { header("Location: ?ctrl=user&act=login"); exit; } $favorites = $this->model->getFavoritesByUser($_SESSION['user']['id']); include_once "./Views/user/favorite_list.php"; }


    function list() {
        $catModel = new Category(); $categories = $catModel->getAll();
        $options = ['keyword'=>$_GET['keyword']??null, 'categories'=>$_GET['category']??[], 'sort'=>$_GET['sort']??'newest', 'min_price'=>null, 'max_price'=>null];
        $productList = $this->model->getFilteredProducts($options);
        include_once "./Views/user/product_list.php";
    }

    function cart() {
        // ... (Logic giỏ hàng giữ nguyên như cũ của bạn) ...
        // Bạn copy lại hàm cart() cũ vào đây nhé, vì hàm đó không liên quan đến Gallery
        if (!empty($_SESSION['cart'])) {
            foreach($_SESSION['cart'] as $k => $v) { if (is_int($k)) { unset($_SESSION['cart']); break; } }
        }
        if (isset($_GET['action']) && $_GET['action'] == 'add') {
            $id = intval($_GET['id']); $size = $_POST['size']??''; $color = $_POST['color']??'Trắng'; $qty = intval($_POST['sl']??1);
            if ($id > 0 && !empty($size) && !empty($color)) {
                $key = $id . '_' . $size . '_' . $color;
                if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
                $_SESSION['cart'][$key] = isset($_SESSION['cart'][$key]) ? $_SESSION['cart'][$key] + $qty : $qty;
                header("Location: ?ctrl=product&act=cart"); exit;
            }
        }
        $cart_items = []; $total_price = 0;
        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $key => $qty) {
                $parts = explode('_', $key);
                if(count($parts) >= 2) {
                    $pro_id = $parts[0]; $pro_size = $parts[1]; $pro_color = $parts[2] ?? 'Trắng';
                    $product = $this->model->getById($pro_id);
                    $variant = $this->model->getVariant($pro_id, $pro_size, $pro_color);
                    if ($product && $variant) {
                        $cart_items[] = ['key'=>$key, 'id'=>$product['id'], 'name'=>$product['name'], 'image'=>$product['image'], 'size'=>$pro_size, 'color'=>$pro_color, 'price'=>$variant['price'], 'quantity'=>$qty, 'total'=>$variant['price']*$qty];
                        $total_price += ($variant['price'] * $qty);
                    }
                }
            }
        }
        include_once "./Views/user/cart.php";
    }
    
}
?>