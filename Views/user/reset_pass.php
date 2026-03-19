<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt lại mật khẩu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include_once "./Views/user/layout_header.php"; ?>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-header bg-success text-white text-center">
                        <h4>ĐẶT MẬT KHẨU MỚI</h4>
                    </div>
                    <div class="card-body p-4">
                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">Mật khẩu mới</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nhập lại mật khẩu</label>
                                <input type="password" name="repassword" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Xác nhận</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once "./Views/user/layout_footer.php"; ?>
</body>
</html>