# 👕 VietnamShirt Shop

VietnamShirt Shop là một dự án website thương mại điện tử chuyên cung cấp các sản phẩm thời trang như áo thun, áo polo, áo khoác, áo sơ mi và áo len. Dự án được xây dựng dựa trên kiến trúc MVC (Model-View-Controller) thuần túy, mang lại trải nghiệm mua sắm mượt mà cho người dùng và cung cấp hệ thống quản lý toàn diện cho quản trị viên.

## 🚀 Tính năng nổi bật

### Dành cho Khách hàng (User)
* **Xác thực:** Đăng ký, Đăng nhập, Khôi phục mật khẩu qua Email (sử dụng PHPMailer).
* **Mua sắm:** Xem danh sách sản phẩm, Xem chi tiết sản phẩm, Thêm vào giỏ hàng.
* **Thanh toán:** Thực hiện quy trình đặt hàng và thanh toán (Checkout).
* **Tương tác:** Thêm sản phẩm vào danh sách yêu thích (Favorite List), Bình luận/Đánh giá sản phẩm.
* **Quản lý cá nhân:** Quản lý hồ sơ cá nhân, Theo dõi lịch sử đơn hàng.

### Dành cho Quản trị viên (Admin)
* **Bảng điều khiển:** Giao diện quản trị riêng biệt.
* **Quản lý Sản phẩm & Danh mục:** Thêm, sửa, xóa sản phẩm và các danh mục thời trang.
* **Quản lý Đơn hàng:** Theo dõi, cập nhật trạng thái và xử lý các đơn hàng từ khách hàng.
* **Quản lý Người dùng:** Xem và quản lý thông tin tài khoản của khách hàng và nhân viên.

## 🛠️ Công nghệ sử dụng
* **Ngôn ngữ chính:** PHP (Mô hình MVC)
* **Cơ sở dữ liệu:** MySQL
* **Giao diện:** HTML, CSS, JavaScript
* **Thư viện hỗ trợ:** PHPMailer (Gửi email hệ thống)

## 📂 Cấu trúc thư mục (MVC)
* `Controllers/`: Chứa các file xử lý logic điều hướng (AdminController, ProductController, OrderController,...).
* `Models/`: Chứa các file tương tác trực tiếp với cơ sở dữ liệu (Database, Product, User, Order,...).
* `Views/`: Chứa giao diện hiển thị cho người dùng và admin.
* `Public/`: Chứa các tài nguyên tĩnh như CSS, JS.
* `image/` & `img/`: Chứa hình ảnh sản phẩm, banner của website.

## ⚙️ Hướng dẫn cài đặt

1.  **Clone mã nguồn về máy local:**
    ```bash
    git clone [https://github.com/your-username/VietnamShirt_shop.git](https://github.com/your-username/VietnamShirt_shop.git)
    ```
2.  **Cài đặt môi trường:**
    Sử dụng XAMPP, WAMP hoặc bất kỳ phần mềm máy chủ ảo nào hỗ trợ PHP và MySQL.
    Di chuyển thư mục dự án vào thư mục `htdocs` (nếu dùng XAMPP) hoặc `www` (nếu dùng WAMP).
3.  **Thiết lập Cơ sở dữ liệu:**
    * Mở phpMyAdmin (thường là `http://localhost/phpmyadmin`).
    * Tạo một cơ sở dữ liệu mới (ví dụ: `vietnamshirt_db`).
    * Import file `.sql` (nếu có trong dự án) để tạo các bảng cần thiết.
4.  **Cấu hình kết nối:**
    * Mở file `Models/Database.php`.
    * Cập nhật thông tin kết nối cơ sở dữ liệu (Tên DB, Username, Password) cho phù hợp với môi trường local của bạn.
5.  **Chạy dự án:**
    Truy cập vào trình duyệt với đường dẫn: `http://localhost/VietnamShirt_shop/`

## 👨‍💻 Về Tác Giả

Em là sinh viên chuyên ngành Back-end Developer, có nền tảng phát triển Web với PHP/Laravel và NodeJS. Có kinh nghiệm xây dựng các chức năng cơ bản như CRUD, RESTful API, làm việc với cơ sở dữ liệu MySQL và MongoDB thông qua các dự án học tập và thực hành, đồng thời thành thạo sử dụng Git và GitHub để quản lý mã nguồn dự án. 

Với tinh thần trách nhiệm, sự cẩn thận, em mong muốn không ngừng học hỏi và đóng góp vào quá trình phát triển sản phẩm của doanh nghiệp. Mục tiêu dài hạn: Nỗ lực trau dồi chuyên môn để có thể trở thành nhân viên chính thức sau kỳ thực tập và em hướng đến việc trở thành một Full Stack Developer, có khả năng giải quyết các vấn đề và mang lại giá trị tốt nhất cho doanh nghiệp.

---
*Cảm ơn bạn đã ghé thăm dự án! Nếu thấy hữu ích, hãy cho repo này một ⭐ nhé!*
