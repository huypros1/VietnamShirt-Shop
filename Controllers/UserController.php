<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
include_once "./Models/User.php";
include_once "./Models/PHPMailer/src/Exception.php";
include_once "./Models/PHPMailer/src/PHPMailer.php";
include_once "./Models/PHPMailer/src/SMTP.php";

class UserController
{
    function forgotPassword() {
        $error = "";
        $success = "";

        if (isset($_POST['email'])) {
            $email = $_POST['email'];
            $userModel = new User();
            
            // Kiểm tra email có tồn tại không
            $checkUser = $userModel->checkEmail($email); 
            
            if ($checkUser) {
                // Tạo mã token ngẫu nhiên
                $token = bin2hex(random_bytes(32)); 
                
                // Lưu token vào DB (Bạn cần viết hàm updateToken bên Model nhé)
                // SQL mẫu: UPDATE users SET reset_token='$token' WHERE email='$email'
                $userModel->updateToken($email, $token);

                // Gửi mail
                $link = "http://localhost/duannghindo/index.php?ctrl=user&act=reset_pass&token=" . $token;
                $body = "Chào bạn,<br>Bấm vào đây để đặt lại mật khẩu: <a href='$link'>$link</a>";
                
                if ($this->sendMail($email, "Quên mật khẩu", $body)) {
                    $success = "Đã gửi mail! Vui lòng kiểm tra hộp thư.";
                } else {
                    $error = "Lỗi gửi mail. Vui lòng thử lại.";
                }
            } else {
                $error = "Email này chưa đăng ký!";
            }
        }
        include "Views/user/forgot_password.php";
    }
    function reset_pass() {
        $token = $_GET['token'] ?? '';
        $userModel = new User();
        
        // Kiểm tra token
        $user = $userModel->checkToken($token);

        if (!$user) {
            $_SESSION['error'] = "Đường dẫn không hợp lệ hoặc đã hết hạn!";
            header("Location: index.php?ctrl=user&act=login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['password'])) {
            $pass = $_POST['password'];
            
            // QUAN TRỌNG: Dùng md5() để mã hóa mật khẩu trước khi lưu
            $userModel->resetPassword($token, md5($pass)); 
            
            $_SESSION['info'] = "Đổi mật khẩu thành công! Hãy đăng nhập.";
            header("Location: index.php?ctrl=user&act=login");
            exit();
        }
        
        include "./Views/user/reset_pass.php";
    }
    // --- HÀM GỬI MAIL (Cấu hình Gmail) ---
    function sendMail($to, $subject, $content) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'khangken226@gmail.com';  // <--- ĐIỀN EMAIL CỦA BẠN
            $mail->Password   = 'dsvt psdl rhtk qurg';        // <--- ĐIỀN MẬT KHẨU ỨNG DỤNG (Xem hướng dẫn dưới)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom('khangken226@gmail.com', 'Admin Shop');
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $content;

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    function login()
    {
        include_once "./Views/user/user_login.php";
    }

    function postLogin()
    {
        // Không cần include lại Model vì đã include ở đầu file rồi
        // include_once "./Models/User.php"; 

        if (isset($_POST['email']) && isset($_POST['password'])) {
            $userModel = new User();
            $user = $userModel->login($_POST['email'], $_POST['password']);

            if ($user) {
                $_SESSION['user'] = $user;
                // Kiểm tra role
                if ($user['role'] === 'admin') {
                    header('Location: admin.php?ctrl=admin&act=page');
                } else {
                    header('Location: index.php?ctrl=page&act=home');
                }
                exit();
            } else {
                // SỬA LỖI 1 & 2: Dùng 'error' thay vì 'Info' để hiện khung đỏ bên View
                // Và dùng header redirect để tránh lỗi resubmit form khi F5
                $_SESSION['error'] = "Email hoặc mật khẩu không đúng.";
                header('Location: ?ctrl=user&act=login');
                exit();
            }
        } else {
            header('Location: index.php?ctrl=user&act=login');
            exit();
        }
    }

    function register()
    {
        include_once "./Views/user/user_register.php";
    }

    function postRegister()
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        // Fix nhỏ: Dùng null coalescing operator cho an toàn
        $password_confirm = $_POST['password_confirm'] ?? ''; 

        if ($password !== $password_confirm) {
            $_SESSION['error'] = "Mật khẩu nhập lại không khớp!";
            header("Location: ?ctrl=user&act=register");
            return;
        }

        $userModel = new User();
        if ($userModel->checkEmail($email)) {
            $_SESSION['error'] = "Email đã được đăng ký";
            header("Location: ?ctrl=user&act=register");
            return;
        }

        $userModel->register($name, $email, $password);
        $_SESSION['info'] = "Đăng ký thành công! Hãy đăng nhập";
        header("Location: ?ctrl=user&act=login");
    }

    function logout()
    {
        // SỬA LỖI 3: Không dùng session_destroy() nếu muốn giữ thông báo
        // session_destroy(); 
        
        unset($_SESSION['user']); // Chỉ xóa biến user
        
        $_SESSION['info'] = "Đăng xuất thành công";
        header("Location: index.php?ctrl=user&act=login"); // Chuyển về trang login thay vì root ./
        exit();
    }

    // function profile()
    // {
    //     $userModel = new User();
    //     $user_id = $_SESSION['user']['id'] ?? null;
    //     $user = null;
    //     if ($user_id) {
    //         $user = $userModel->profile($user_id);
    //     }
    //     include_once "./Views/user/user_profile.php";
    // }

    public function myOrders()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: ?ctrl=user&act=login");
            exit;
        }
        $user_id = $_SESSION['user']['id'];
        $userModel = new User();
        $orders = $userModel->myOrders($user_id);
        include_once "./Views/user/user_order.php";
    }

    function password()
    {
        include_once "./Views/user/user_password.php";
    }
    
    
    // update thông tin 

public function updateProfile()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: ?ctrl=user&act=login");
            exit();
        }

        $userModel = new User();
        $user = $userModel->profile($_SESSION['user']['id']);

        // Bắt buộc dùng require để $user được truyền vào view
        require "./Views/user/user_profile.php";
    }

    public function postUpdateProfile()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: ?ctrl=user&act=login");
            exit();
        }

        $user_id  = $_SESSION['user']['id'];
        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $phone    = trim($_POST['phone'] ?? '');
        $address  = trim($_POST['address'] ?? '');
        $birthday = $_POST['birthday'] ?? null;
        $gender   = $_POST['gender'] ?? 'khac';

        // Validate
        if (empty($name) || empty($email) || empty($phone)) {
            $_SESSION['error'] = "Vui lòng điền đầy đủ họ tên, email và số điện thoại!";
            header("Location: ?ctrl=user&act=updateProfile");
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Email không hợp lệ!";
            header("Location: ?ctrl=user&act=updateProfile");
            exit();
        }

        $userModel = new User();

        // Kiểm tra email trùng (trừ chính user này)
        $check = $userModel->checkEmail($email);
        if ($check && $check['id'] != $user_id) {
            $_SESSION['error'] = "Email này đã được sử dụng bởi tài khoản khác!";
            header("Location: ?ctrl=user&act=updateProfile");
            exit();
        }

        // Cập nhật vào DB
        $result = $userModel->updateUserProfile($user_id, $name, $email, $phone, $address, $birthday, $gender);

        if ($result) {
            // Cập nhật luôn session để hiển thị ngay
            $_SESSION['user']['name']     = $name;
            $_SESSION['user']['email']    = $email;
            $_SESSION['user']['phone']    = $phone;
            $_SESSION['user']['address']  = $address;
            $_SESSION['user']['birthday'] = $birthday;
            $_SESSION['user']['gender']   = $gender;

            $_SESSION['info'] = "Cập nhật thông tin cá nhân thành công!";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra khi cập nhật. Vui lòng thử lại!";
        }

        header("Location: ?ctrl=user&act=updateProfile");
        exit();
    }
          function postPassword()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: ?ctrl=user&act=login");
            exit();
        }

        $user_id = $_SESSION['user']['id'];
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Validate cơ bản
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $_SESSION['error'] = "Vui lòng điền đầy đủ các trường!";
            header("Location: ?ctrl=user&act=password");
            exit();
        }

        if ($new_password !== $confirm_password) {
            $_SESSION['error'] = "Mật khẩu mới và xác nhận không khớp!";
            header("Location: ?ctrl=user&act=password");
            exit();
        }

        if (strlen($new_password) < 6) {
            $_SESSION['error'] = "Mật khẩu mới phải có ít nhất 8 ký tự!";
            header("Location: ?ctrl=user&act=password");
            exit();
        }

        $userModel = new User();

        // Lấy thông tin user hiện tại để kiểm tra mật khẩu cũ
        $user = $userModel->profile($user_id);

        // Kiểm tra mật khẩu hiện tại có đúng không (dùng md5 vì hiện tại đang lưu md5)
        if ($user['password'] !== md5($current_password)) {
            $_SESSION['error'] = "Mật khẩu hiện tại không đúng!";
            header("Location: ?ctrl=user&act=password");
            exit();
        }

        // Không cho phép mật khẩu mới trùng mật khẩu cũ
        if (md5($new_password) === $user['password']) {
            $_SESSION['error'] = "Mật khẩu mới không được trùng với mật khẩu cũ!";
            header("Location: ?ctrl=user&act=password");
            exit();
        }

        // Cập nhật mật khẩu mới (vẫn dùng md5 để đồng bộ với hệ thống hiện tại)
        $hashed_new = md5($new_password);
        $result = $userModel->updatePassword($user_id, $hashed_new);

        if ($result) {
            // Cập nhật luôn session nếu cần (tùy chọn)
            $_SESSION['user']['password'] = $hashed_new; // Không thực sự cần vì không dùng để hiển thị

            $_SESSION['info'] = "Đổi mật khẩu thành công!";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra khi cập nhật mật khẩu. Vui lòng thử lại!";
        }

        header("Location: ?ctrl=user&act=password");
        exit();
    }
}