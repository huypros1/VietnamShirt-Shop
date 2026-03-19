<?php 
include_once("./Controllers/ProductController.php")
?>

<main class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Chi tiết đơn hàng #<?= $order['id'] ?></h3>
            <a href="?ctrl=user&act=myOrders" class="btn btn-outline-dark">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">Danh sách sản phẩm</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Giá</th>
                                        <th>SL</th>
                                        <th class="text-end">Tạm tính</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $total_product_price = 0;
                                    foreach ($items as $item): 
                                        $subtotal = $item['price'] * $item['quantity'];
                                        $total_product_price += $subtotal;
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="Public/image/<?= $item['image'] ?>" 
                                                     style="width: 60px; height: 60px; object-fit: cover;" 
                                                     class="rounded me-3">
                                                <div>
                                                    <h6 class="mb-0"><?= $item['name'] ?></h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= number_format($item['price'], 0, ',', '.') ?>₫</td>
                                        <td>x <?= $item['quantity'] ?></td>
                                        <td class="text-end fw-bold"><?= number_format($subtotal, 0, ',', '.') ?>₫</td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        <div class="col-12 mb-4">
            <h3 class="fw-bold text-uppercase">Đánh giá sản phẩm</h3>
        </div>

        <div class="col-lg-4 col-md-5 mb-4">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-3">Viết đánh giá của bạn</h5>
                    
                    <?php if(isset($_SESSION['user'])): ?>
                        <?php if(isset($error)) echo "<div class='alert alert-danger py-2'>$error</div>"; ?>
                        
                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label fw-medium">Chọn mức độ hài lòng:</label>
                                <select name="rating" class="form-select form-select-lg">
                                    <option value="5">⭐⭐⭐⭐⭐ - Tuyệt vời</option>
                                    <option value="4">⭐⭐⭐⭐ - Tốt</option>
                                    <option value="3">⭐⭐⭐ - Bình thường</option>
                                    <option value="2">⭐⭐ - Tệ</option>
                                    <option value="1">⭐ - Rất tệ</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">Nội dung đánh giá:</label>
                                <textarea name="content" class="form-control" rows="5" placeholder="Chia sẻ cảm nhận của bạn về sản phẩm..." required></textarea>
                            </div>
                            <button type="submit" name="submit_comment" class="btn btn-primary w-100 btn-lg">Gửi đánh giá</button>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-warning text-center">
                            Vui lòng <a href="?ctrl=user&act=login" class="fw-bold text-dark">đăng nhập</a> để viết đánh giá.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        </div>
    </div>
</main>