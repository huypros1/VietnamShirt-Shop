<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quên mật khẩu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include_once "./Views/user/layout_header.php"; ?>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-header bg-danger text-white text-center">
                        <h4>KHÔI PHỤC MẬT KHẨU</h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger">
                                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['info'])): ?>
                            <div class="alert alert-success">
                                <?= $_SESSION['info']; unset($_SESSION['info']); ?>
                            </div>
                        <?php endif; ?>

                        <p class="text-muted text-center">
                            Nhập email đã đăng ký của bạn. Hệ thống sẽ gửi một liên kết để đặt lại mật khẩu.
                        </p>

                        <form action="?ctrl=user&act=forgotPassword" method="post">
                            <div class="mb-3">
                                <label class="form-label">Email của bạn</label>
                                <input type="email" name="email" class="form-control" required placeholder="vidu@gmail.com">
                            </div>
                            <button type="submit" class="btn btn-danger w-100">Gửi yêu cầu</button>
                        </form>
                        
                        <div class="text-center mt-3">
                            <a href="?ctrl=user&act=login" class="text-decoration-none">Quay lại đăng nhập</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once "./Views/user/layout_footer.php"; ?>
</body>
</html>