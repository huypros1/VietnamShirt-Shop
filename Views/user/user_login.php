<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập tài khoản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include_once "./Views/user/layout_header.php"; ?>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <h3 class="text-center mb-4 text-danger">
                            <i class="fas fa-user me-2"></i>Đăng nhập
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

                       <form action="?ctrl=user&act=postLogin" method="post">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control" required 
                                       placeholder="Nhập email của bạn">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" name="password" id="password" class="form-control" required 
                                       placeholder="Nhập mật khẩu">
                                
                                <div class="text-end mt-2">
                                    <a href="?ctrl=user&act=forgotPassword" class="text-decoration-none text-danger small">
                                        Quên mật khẩu?
                                    </a>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-danger w-100 mb-3">
                                <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                            </button>
                        </form>
                        <div class="text-center">
                            <p class="mb-0">Chưa có tài khoản? 
                                <a href="?ctrl=user&act=register" class="text-danger fw-bold">Đăng ký ngay</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once "./Views/user/layout_footer.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>