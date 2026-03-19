<?php
include_once "./Models/Database.php";
class Category
{
  private $db;
  function __construct()
  {
    $this->db = new Database();
  }

  // function getAllCategory()
  // {
  //   $sql = "SELECT * FROM category ORDER BY id";
  //   return $this->db->query($sql);
  // }
  public function getAll()
    {
        $sql = "SELECT 
                    c.*,
                    COALESCE(p.product_count, 0) AS product_count,
                    parent.name AS parent_name
                FROM category c
                LEFT JOIN (
                    SELECT category_id, COUNT(*) AS product_count 
                    FROM products 
                    GROUP BY category_id
                ) p ON c.id = p.category_id
                LEFT JOIN category parent ON c.parent_id = parent.id
                ORDER BY c.parent_id ASC, c.sort_order ASC, c.id ASC";

        return $this->db->query($sql);
    }

    // Lấy 1 danh mục theo id
    public function getById($id)
    {
        $sql = "SELECT * FROM category WHERE id = ?";
        return $this->db->queryOne($sql, [$id]);
    }

    // Thêm mới
    public function insert($data)
    {
        $sql = "INSERT INTO category (name, parent_id, status, sort_order) 
                VALUES (?, ?, ?, ?)";
        $this->db->query($sql, [
            $data['name'],
            $data['parent_id'] ?: null,
            $data['status'] ?? 1,
            $data['sort_order'] ?? 0
        ]);
    }

    // Cập nhật
    public function update($id, $data)
    {
        $sql = "UPDATE category 
                SET name = ?, parent_id = ?, status = ?, sort_order = ?
                WHERE id = ?";
        $this->db->query($sql, [
            $data['name'],
            $data['parent_id'] ?: null,
            $data['status'],
            $data['sort_order'] ?? 0,
            $id
        ]);
    }

    // Xóa
    public function delete($id)
    {
        // Nên kiểm tra có sản phẩm hoặc danh mục con trước khi xóa thật tế
        $sql = "DELETE FROM category WHERE id = ?";
        $this->db->query($sql, [$id]);
    }

    // Toggle trạng thái (1 ↔ 0)
    public function toggleStatus($id)
    {
        $sql = "UPDATE category SET status = 1 - status WHERE id = ?";
        $this->db->query($sql, [$id]);
    }
    // Lấy danh mục đang hiển thị (status = 1) và danh mục cha cũng phải hiển thị
public function getVisibleCategories()
{
    $all = $this->getAll();

    $visibleIds = [];
    $hiddenParentIds = [];

    // Tìm các danh mục cha bị ẩn
    foreach ($all as $cat) {
        if ($cat['status'] == 0) {
            $hiddenParentIds[] = $cat['id'];
            // Tìm luôn các con của nó để ẩn
            $this->collectChildrenIds($all, $cat['id'], $hiddenParentIds);
        }
    }

    foreach ($all as $cat) {
        if ($cat['status'] == 1 && !in_array($cat['id'], $hiddenParentIds)) {
            $visibleIds[] = $cat['id'];
        }
    }

    // Trả về danh mục đầy đủ thông tin, chỉ giữ những cái visible
    return array_filter($all, function($cat) use ($visibleIds) {
        return in_array($cat['id'], $visibleIds);
    });
}

// Hàm hỗ trợ đệ quy thu thập ID con
private function collectChildrenIds($categories, $parentId, &$hiddenIds)
{
    foreach ($categories as $cat) {
        if ($cat['parent_id'] == $parentId) {
            $hiddenIds[] = $cat['id'];
            $this->collectChildrenIds($categories, $cat['id'], $hiddenIds);
        }
    }
}
  
}