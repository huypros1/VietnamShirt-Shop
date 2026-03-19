<?php
include_once "./Models/Database.php";

class Admin
{
    private $db;
    private $conn;
    function __construct() {
        $this->db = new Database();
    }

    // --- CÁC HÀM CƠ BẢN ---
    function getAllCategory() { return $this->db->query("SELECT * FROM category"); }
    function getById($id) { return $this->db->queryOne("SELECT * FROM products WHERE id = ?", [$id]); }
    function getVariants($id) { return $this->db->query("SELECT * FROM product_variants WHERE product_id = ?", [$id]); }
    function getTotalProducts() { $row = $this->db->queryOne("SELECT COUNT(*) as total FROM products"); return $row['total']; }
    function search($keyword) { $keyword = trim($keyword); if ($keyword == "") return []; return $this->db->query("SELECT * FROM products WHERE name LIKE ?", ["%$keyword%"]); }
    
    function update($id, $data, $imageString) {
        $finalImage = ($imageString) ? $imageString : $data['current_image'];
        $sql = "UPDATE products SET name=?, description=?, quantity=?, image=?, category_id=? WHERE id=?";
        $params = [$data['name'], $data['description'], $data['quantity'], $finalImage, $data['category_id'], $id];
        return $this->db->query($sql, $params);
    }

    // Hàm cập nhật trạng thái Ẩn/Hiện
    function updateStatus($id, $status) {
        $sql = "UPDATE products SET status = ? WHERE id = ?";
        return $this->db->query($sql, [$status, $id]);
    }
    
function insert($data, $imgString) {
        $slug = strtolower(str_replace(' ', '-', $data['name']));
        
        $sql = "INSERT INTO products (name, category_slug, description, image, category_id, quantity, sold, created_at, status) 
                VALUES (?, ?, ?, ?, ?, ?, 0, NOW(), 1)"; 
        
        $params = [
            $data['name'], 
            $slug, 
            $data['description'] ?? '', 
            $imgString, 
            $data['category_id'], 
            $data['quantity']
        ];
        
        // 1. Chạy câu lệnh Insert
        $this->db->query($sql, $params);
        
        // 2. Lấy ID vừa tạo (Gọi hàm mới thêm bên Database)
        return $this->db->lastInsertId(); 
    }
    public function lastInsertId() {
        return $this->conn->lastInsertId();
    }
    function delete($id) {
        try {
            $this->db->delete("DELETE FROM product_variants WHERE product_id = ?", [$id]);
            return $this->db->delete("DELETE FROM products WHERE id = ?", [$id]);
        } catch (Exception $e) { return false; }
    }

    // --- HÀM LƯU BIẾN THỂ ---
    function saveVariants($product_id, $variantsData) {
        $totalQty = 0; // Biến tính tổng số lượng hiển thị ra bên ngoài

        if (!empty($variantsData) && is_array($variantsData)) {
            foreach ($variantsData as $v) {
                $color = trim($v['color']);
                $size  = trim($v['size']);
                $price = (int)$v['price'];
                $stock = (int)$v['stock'];
                $image = isset($v['image']) ? $v['image'] : '';
                
                // [THAY ĐỔI] Lấy trạng thái từ dữ liệu gửi lên (mặc định là 'show')
                $status = (isset($v['status']) && $v['status'] === 'hide') ? 'hide' : 'show';

                // [QUAN TRỌNG] Bỏ đoạn code "if remove == 1 then stock = 0" 
                // Vì bây giờ ta dùng status='hide' để ẩn, không cần xóa stock.

                if (!empty($color) && !empty($size) && ($price >= 0 || $stock >= 0)) {
                    
                    if (isset($v['id']) && !empty($v['id'])) {
                        // [CẬP NHẬT] Thêm cột status vào câu UPDATE
                        $sql = "UPDATE product_variants SET size=?, color=?, price=?, stock=?, image=?, status=? WHERE id=?";
                        $params = [$size, $color, $price, $stock, $image, $status, $v['id']];
                    } else {
                        // [CẬP NHẬT] Thêm cột status vào câu INSERT
                        $sql = "INSERT INTO product_variants (product_id, size, color, price, stock, image, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
                        $params = [$product_id, $size, $color, $price, $stock, $image, $status];
                    }
                    
                    // Thực thi câu lệnh
                    $this->db->query($sql, $params);
                    
                    // [LOGIC MỚI] Chỉ cộng dồn số lượng vào tổng SP nếu trạng thái là SHOW
                    // Nếu HIDE thì sản phẩm chính sẽ không đếm số lượng này (khách không mua được)
                    if ($status === 'show') {
                        $totalQty += $stock;
                    }
                }
            }
        }
        
        // Cập nhật tổng số lượng hiển thị vào bảng products
        $this->db->query("UPDATE products SET quantity = ? WHERE id = ?", [$totalQty, $product_id]);
    }
       function getTopSellingProducts($limit = 10) {
        $sql = "SELECT 
                    p.name, 
                    COALESCE(SUM(oi.quantity), 0) AS total_sold
                FROM order_item oi
                JOIN product_variants v ON oi.variant_id = v.id
                JOIN products p ON v.product_id = p.id
                JOIN orders o ON oi.order_id = o.id
                WHERE o.status = 'completed'
                GROUP BY p.id, p.name
                ORDER BY total_sold DESC
                LIMIT $limit"; // limit là số nguyên do ta truyền, nối chuỗi an toàn
        return $this->db->query($sql);
    }

    function query($sql, ...$params) { if (isset($params[0]) && is_array($params[0])) { $params = $params[0]; } return $this->db->query($sql, $params); }
    function queryOne($sql, ...$params) { if (isset($params[0]) && is_array($params[0])) { $params = $params[0]; } return $this->db->queryOne($sql, $params); }
}
?>