<?php
include_once "./Models/Database.php";

class User
{
    private $db;

    function __construct()
    {
        $this->db = new Database();
    }

    // function login($email, $password)
    // {
    //     $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
    //     return $this->db->queryOne($sql, $email, md5($password));
    // }

    function register($name, $email, $password)
    {
        $sql = "INSERT INTO users (`name`, `email`, `password`) VALUES (?, ?, ?)";
        return $this->db->insert($sql, $name, $email, md5($password));
    }

    function checkEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        return $this->db->queryOne($sql, $email);
    }
    function updateToken($email, $token) {
        // Token hết hạn sau 15 phút
        $sql = "UPDATE users 
                SET reset_token = ?, 
                    reset_token_exp = DATE_ADD(NOW(), INTERVAL 15 MINUTE) 
                WHERE email = ?";
        $this->db->query($sql, $token, $email);
    }

    // 2. Kiểm tra Token có hợp lệ không (Dùng cho trang Đổi mật khẩu)
    function checkToken($token) {
        $sql = "SELECT * FROM users 
                WHERE reset_token = ? 
                AND reset_token_exp > NOW()"; 
        return $this->db->queryOne($sql, $token);
    }

    // 3. Đổi mật khẩu mới
    function resetPassword($token, $newPassword) {
    $sql = "UPDATE users 
            SET password = ?, reset_token = NULL, reset_token_exp = NULL 
            WHERE reset_token = ?";
    
    // LỖI Ở ĐÂY: Bạn đang lưu pass thô (chưa mã hóa)
    $this->db->query($sql, $newPassword, $token); 
}

    function profile($user_id)
    {
        $sql = "SELECT * FROM users WHERE id = ?";
        return $this->db->queryOne($sql, $user_id);
    }

    function myOrders($user_id)
    {
        $sql = "SELECT o.id, o.created_at, o.total_amount, o.status, o.address, o.phone,
                GROUP_CONCAT(p.name SEPARATOR ', ') as product_list 
                FROM orders o
                LEFT JOIN order_item oi ON o.id = oi.order_id
                LEFT JOIN products p ON oi.product_id = p.id
                WHERE o.user_id = ?   
                GROUP BY o.id
                ORDER BY o.created_at DESC";
        return $this->db->query($sql, $user_id);
    }

    // Hàm cập nhật thông tin – ĐÃ HOÀN CHỈNH & AN TOÀN
    function updateUserProfile($user_id, $name, $email, $phone, $address, $birthday = null, $gender = 'khac')
    {
        $sql = "UPDATE users 
                SET name = ?, 
                    email = ?, 
                    phone = ?, 
                    address = ?, 
                    birthday = ?, 
                    gender = ? 
                WHERE id = ?";

        // Nếu ngày sinh rỗng → để NULL cho DB
        if (empty($birthday)) {
            $birthday = null;
        }

        return $this->db->update($sql, $name, $email, $phone, $address, $birthday, $gender, $user_id);
    }
    function getAllUsers($keyword = '', $page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT id, name, email, role, created_at FROM users";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " WHERE name LIKE ? OR email LIKE ?";
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
        }

        $sql .= " ORDER BY id DESC LIMIT $perPage OFFSET $offset";
        return $this->db->query($sql, $params);
    }

    // Đếm tổng số người dùng (cho phân trang)
    function countAllUsers($keyword = '')
    {
        $sql = "SELECT COUNT(*) as total FROM users";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " WHERE name LIKE ? OR email LIKE ?";
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
        }

        $row = $this->db->queryOne($sql, $params);
        return $row['total'];
    }

    // Lấy user theo ID
    function getUserById($id)
    {
        return $this->db->queryOne("SELECT id, name, email, role FROM users WHERE id = ?", [$id]);
    }

    // Thêm người dùng mới (admin tạo)
    function createUser($name, $email, $password, $role = 'user')
    {
        $hashed = md5($password); // Bạn nên chuyển sang password_hash() sau này
        $sql = "INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())";
        return $this->db->query($sql, [$name, $email, $hashed, $role]);
    }

    // Cập nhật thông tin người dùng (không đổi mật khẩu)
    function updateUser($id, $name, $email, $role)
    {
        $sql = "UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?";
        return $this->db->query($sql, [$name, $email, $role, $id]);
    }

    // Cập nhật quyền (dùng cho AJAX)
    function updateRole($id, $role)
    {
        if (!in_array($role, ['user', 'admin'])) return false;
        return $this->db->query("UPDATE users SET role = ? WHERE id = ?", [$role, $id]);
    }

    // Xóa người dùng
    function deleteUser($id)
    {
        return $this->db->query("DELETE FROM users WHERE id = ?", [$id]);
    }
        // Cập nhật trạng thái cấm/mở cấm
    function toggleBanStatus($id)
    {
        $sql = "UPDATE users SET status = 1 - status WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }

    // Lấy trạng thái hiện tại
    function getStatus($id)
    {
        $row = $this->db->queryOne("SELECT status FROM users WHERE id = ?", [$id]);
        return $row['status'] ?? 1;
    }

    // Sửa hàm login: kiểm tra status
    function login($email, $password)
    {
        $sql = "SELECT * FROM users WHERE email = ? AND password = ? AND status = 1";
        return $this->db->queryOne($sql, [$email, md5($password)]);
    }

    // Hoặc nếu bạn muốn thông báo rõ lý do bị cấm, có thể tách riêng kiểm tra
    // Nhưng cách trên đơn giản và hiệu quả: nếu bị cấm thì không tìm thấy user → login thất bại
       // Kiểm tra mật khẩu hiện tại có đúng không
    function checkCurrentPassword($user_id, $current_password)
    {
        $user = $this->db->queryOne("SELECT password FROM users WHERE id = ?", [$user_id]);
        if ($user) {
            // Nếu mật khẩu cũ lưu bằng md5 (tương thích hệ thống cũ)
            if (md5($current_password) === $user['password']) {
                return true;
            }
            // Nếu đã dùng password_hash (tương lai hoặc đã cập nhật)
            if (password_verify($current_password, $user['password'])) {
                return true;
            }
        }
        return false;
    }

    // Cập nhật mật khẩu mới (an toàn, dùng password_hash)
    function updatePassword($user_id, $new_hashed_password)
    {
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        return $this->db->update($sql, $new_hashed_password, $user_id);
    }
}