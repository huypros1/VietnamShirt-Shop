<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng ký tài khoản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include_once "./Views/user/layout_header.php"; ?>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <h3 class="text-center mb-4 text-danger">
                            <i class="fas fa-user-plus me-2"></i>Đăng ký tài khoản
                        </h3>

                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['info'])): ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <?= $_SESSION['info']; unset($_SESSION['info']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form action="?ctrl=user&act=postRegister" method="post">
                            <div class="mb-3">
                                <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" required 
                                       placeholder="Nguyễn Văn A">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control" required 
                                       placeholder="example@gmail.com">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" name="password" id="password" class="form-control" required 
                                       minlength="6" placeholder="tối thiểu 6 ký tự">
                            </div>

                            <div class="mb-4">
                                <label for="password_confirm" class="form-label">Nhập lại mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirm" id="password_confirm" class="form-control" required 
                                       minlength="6">
                            </div>

                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-user-plus me-2"></i>Đăng ký ngay
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <p class="mb-0">Đã có tài khoản? 
                                <a href="?ctrl=user&act=login" class="text-danger fw-bold">Đăng nhập</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once "./Views/user/layout_footer.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Kiểm tra mật khẩu trùng khớp (client-side) -->
    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const pass = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirm').value;
            if (pass !== confirm) {
                e.preventDefault();
                alert('Mật khẩu nhập lại không khớp!');
            }
        });
    </script>
</body>
</html>