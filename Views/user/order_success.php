<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt hàng thành công!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
<div class="container text-center py-5 my-5">
    <div class="py-5 bg-white shadow-sm rounded">
        <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
        <h1 class="mt-4 text-success fw-bold">Đặt hàng thành công!</h1>
        <h5 class="mt-3">Mã đơn hàng: <strong class="text-danger">#<?= $order_id ?></strong></h5>
        
        <p class="lead mt-3 mb-4">Cảm ơn bạn đã mua sắm tại Shop!</p>
        
        <div class="row justify-content-center">
            <div class="col-md-6 text-muted">
                <p class="mb-1"><i class="fas fa-phone-alt me-1"></i> Chúng tôi sẽ liên hệ xác nhận sớm nhất.</p>
                <p><i class="fas fa-money-bill-wave me-1"></i> Bạn vui lòng chuẩn bị tiền mặt khi nhận hàng.</p>
            </div>
        </div>

        <div class="mt-4">
            <a href="?ctrl=page&act=home" class="btn btn-outline-dark btn-lg px-4 me-2">
                <i class="fas fa-home"></i> Trang chủ
            </a>
            <a href="?ctrl=order&act=detail&id=<?= $order_id ?>" class="btn btn-danger btn-lg px-4">
                <i class="fas fa-file-invoice"></i> Xem chi tiết đơn
            </a>
        </div>
    </div>
</div>
</body>
</html>