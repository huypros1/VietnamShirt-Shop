<?php
include_once "./Models/Database.php";

class Product
{
    private $db;

    function __construct() { $this->db = new Database(); }
    
    // Hàm rút gọn cho SELECT
    function query($sql, ...$args) { return $this->db->query($sql, ...$args); }

    // Logic lấy giá (ưu tiên size S)
    private function getPriceQuery() {
        return "COALESCE(
            (SELECT price FROM product_variants WHERE product_id = p.id AND size = 'S' LIMIT 1),
            (SELECT MIN(price) FROM product_variants WHERE product_id = p.id)
        ) as price";
    }

    function getById($id) {
        $sql = "SELECT p.*, " . $this->getPriceQuery() . " FROM products p WHERE p.id = ?";
        return $this->db->queryOne($sql, $id);
    }

    public function getVariants($product_id) {
    $sql = "SELECT * FROM product_variants 
            WHERE product_id = ? 
            AND status = 'show' 
            ORDER BY price ASC";
            
    return $this->query($sql, [$product_id]);
}

    // --- HỎI ĐÁP ---
    function getQuestions($product_id) {
        $sql = "SELECT q.*, u.name as user_name, u.role FROM questions q JOIN users u ON q.user_id = u.id WHERE q.product_id = ? AND q.parent_id IS NULL ORDER BY q.created_at DESC";
        return $this->query($sql, $product_id);
    }

    function getQuestionReplies($question_id) {
        $sql = "SELECT q.*, u.name as user_name, u.role FROM questions q JOIN users u ON q.user_id = u.id WHERE q.parent_id = ? ORDER BY q.created_at ASC";
        return $this->query($sql, $question_id);
    }

    function addQuestion($user_id, $product_id, $content, $parent_id = null) {
        $sql = "INSERT INTO questions (user_id, product_id, content, parent_id, created_at) VALUES (?, ?, ?, ?, NOW())";
        return $this->db->insert($sql, $user_id, $product_id, $content, $parent_id);
    }

    function replyQuestion($question_id, $reply) {
        $sql = "UPDATE questions SET reply = ? WHERE id = ?";
        return $this->db->update($sql, $reply, $question_id);
    }

    public function getComments($product_id) {
        $sql = "SELECT c.*, u.name as user_name FROM comments c JOIN users u ON c.user_id = u.id WHERE c.product_id = ? ORDER BY c.created_at DESC";
        return $this->query($sql, $product_id);
    }

    public function insertComment($product_id, $user_id, $content, $rating) {
    $sql = "INSERT INTO comments (product_id, user_id, content, rating, created_at) VALUES (?, ?, ?, ?, NOW())";
    return $this->db->query($sql, $product_id, $user_id, $content, $rating);
}

   // --- YÊU THÍCH (FIX LỖI XÓA 1 RA 2) ---
    function checkFavorite($user_id, $product_id, $size = '', $color = '') { 
        // SỬA: Chuẩn hóa input: Nếu size/color là NULL, coi như ''
        $size = $size ?? '';
        $color = $color ?? '';

        // SỬA: Sử dụng COALESCE để xử lý NULL trong CSDL như ''
        $sql = "SELECT count(*) as count FROM favorites 
                WHERE user_id = ? AND product_id = ? 
                AND COALESCE(size, '') LIKE ? 
                AND COALESCE(color, '') LIKE ?"; 
        $result = $this->db->queryOne($sql, $user_id, $product_id, $size, $color); 
        return $result['count'] > 0; 
    }

    function toggleFavorite($user_id, $product_id, $size = '', $color = '') { 
        // SỬA: Chuẩn hóa input
        $size = $size ?? '';
        $color = $color ?? '';

        if ($this->checkFavorite($user_id, $product_id, $size, $color)) { 
            // SỬA: Xóa với COALESCE để xóa cả NULL và ''
            $this->db->delete("DELETE FROM favorites 
                               WHERE user_id = ? AND product_id = ? 
                               AND COALESCE(size, '') = ? 
                               AND COALESCE(color, '') = ?", 
                               $user_id, $product_id, $size, $color); 
            return 'removed';
        } else { 
            // SỬA: Thêm mới, luôn dùng '' nếu rỗng
            $this->db->insert("INSERT INTO favorites (user_id, product_id, size, color, created_at) 
                               VALUES (?, ?, ?, ?, NOW())", 
                               $user_id, $product_id, $size, $color); 
            return 'added';
        } 
    }

    function getFavoritesByUser($user_id) { 
        // SỬA: Thêm COALESCE để lấy size/color như '' nếu NULL
        $sql = "SELECT 
                    p.id, p.name, p.image as default_image,
                    f.created_at as liked_at, COALESCE(f.size, '') as liked_size, COALESCE(f.color, '') as liked_color,
                    COALESCE(
                        NULLIF((SELECT image FROM product_variants v WHERE v.product_id = p.id AND v.size = f.size AND v.color = f.color LIMIT 1), ''), 
                        NULLIF((SELECT image FROM product_variants v WHERE v.product_id = p.id AND v.color = f.color AND image != '' LIMIT 1), ''),
                        p.image
                    ) as image,
                    COALESCE(
                        (SELECT price FROM product_variants v WHERE v.product_id = p.id AND v.size = f.size AND v.color = f.color LIMIT 1), 
                        (SELECT MIN(price) FROM product_variants WHERE product_id = p.id)
                    ) as price
                FROM products p 
                JOIN favorites f ON p.id = f.product_id 
                WHERE f.user_id = ? 
                ORDER BY f.created_at DESC"; 
        return $this->query($sql, $user_id); 
    }

    // --- CÁC HÀM GET KHÁC GIỮ NGUYÊN (VÌ LÀ SELECT NÊN DÙNG QUERY ĐƯỢC) ---
    function getAllProducts() { $sql = "SELECT p.*, " . $this->getPriceQuery() . " FROM products p ORDER BY p.id DESC"; return $this->db->query($sql); }
    function BestSelling($limit = 4) { $sql = "SELECT p.*, " . $this->getPriceQuery() . " FROM products p WHERE p.status = 1 ORDER BY p.sold DESC LIMIT $limit"; return $this->db->query($sql); }
    function NewProducts($limit = 4) { $sql = "SELECT p.*, " . $this->getPriceQuery() . " FROM products p WHERE p.status = 1 ORDER BY p.created_at DESC LIMIT $limit"; return $this->db->query($sql); }
    function RecommendedProducts($limit = 4) { $sql = "SELECT p.*, " . $this->getPriceQuery() . " FROM products p WHERE p.status = 1 ORDER BY RAND() LIMIT $limit"; return $this->db->query($sql); }
    
    function getFilteredProducts($options = []) {
        $sql = "SELECT p.*, MIN(pv.price) as price FROM products p JOIN product_variants pv ON p.id = pv.product_id WHERE 1=1";
        $params = [];
        if (!empty($options['keyword'])) { $sql .= " AND p.name LIKE ?"; $params[] = '%' . $options['keyword'] . '%'; }
        if (!empty($options['categories']) && is_array($options['categories'])) { $placeholders = implode(',', array_fill(0, count($options['categories']), '?')); $sql .= " AND p.category_id IN ($placeholders)"; $params = array_merge($params, $options['categories']); }
        $sql .= " GROUP BY p.id ";
        $sort = $options['sort'] ?? 'newest';
        switch ($sort) {
            case 'price-asc': $sql .= " ORDER BY MIN(pv.price) ASC"; break;
            case 'price-desc': $sql .= " ORDER BY MIN(pv.price) DESC"; break;
            case 'name-asc': $sql .= " ORDER BY p.name ASC"; break;
            default: $sql .= " ORDER BY p.id DESC"; break;
        }
        return $this->db->query($sql, ...$params);
    }

    function getRelatedProducts($category_id, $current_product_id, $limit = 4) {
        if (empty($category_id)) return [];
        $sql = "SELECT p.*, " . $this->getPriceQuery() . " FROM products p WHERE p.category_id = ? AND p.id != ? AND p.status = 1 ORDER BY RAND() LIMIT $limit";
        return $this->db->query($sql, $category_id, $current_product_id);
    }

    function getVariant($product_id, $size, $color) { 
        $sql = "SELECT * FROM product_variants WHERE product_id = ? AND size = ? AND color = ?"; 
        $result = $this->db->query($sql, $product_id, $size, $color); 
        return isset($result[0]) ? $result[0] : null; 
    }    
    function decreaseVariantStock($variant_id, $quantity) {
        $sql = "UPDATE product_variants SET stock = stock - ? WHERE id = ?";
        $this->db->update($sql, $quantity, $variant_id);

        $sql_sold = "UPDATE products p 
                     JOIN product_variants pv ON p.id = pv.product_id 
                     SET p.sold = p.sold + ? 
                     WHERE pv.id = ?";
        $this->db->update($sql_sold, $quantity, $variant_id);
    }
    public function getSizes($product_id) {
    $sql = "SELECT DISTINCT size FROM product_variants 
            WHERE product_id = ? 
            AND status = 'show'"; 
    return $this->query($sql, [$product_id]);
}

function getSizeGuide() { return $this->db->query("SELECT * FROM size_guide ORDER BY id ASC"); }
}
?>