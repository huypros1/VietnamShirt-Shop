
<body>
<?php include_once "./Views/user/layout_header.php"; ?>

<div class="container my-5">
    <h2 class="mb-4"><i class="fas fa-credit-card"></i> Thanh toán khi nhận hàng (COD)</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="row">
        <!-- Form thông tin giao hàng -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Thông tin nhận hàng</h5>
                    <form action="?ctrl=order&act=placeOrder" method="post">
                        <div class="mb-3">
                            <label class="form-label">Họ tên</label>
                            <input type="text" class="form-control" value="<?= $_SESSION['user']['name'] ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control" required placeholder="Ví dụ: 0901234567">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ giao hàng <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control" rows="3" required placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ghi chú (tùy chọn)</label>
                            <textarea name="note" class="form-control" rows="2" placeholder="Giao giờ hành chính, gọi trước khi giao..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-danger btn-lg w-100">
                            <i class="fas fa-check"></i> HOÀN TẤT ĐẶT HÀNG (Thanh toán khi nhận hàng)
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Giỏ hàng tóm tắt -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5>Đơn hàng (<?= count($items) ?> sản phẩm)</h5>
                    <div class="list-group list-group-flush max-h-400 overflow-auto">
                        <?php foreach ($items as $item): ?>
                            <div class="list-group-item py-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong><?= $item['product']['name'] ?></strong>
                                        <small class="text-muted">x <?= $item['quantity'] ?></small>
                                    </div>
                                    <span class="text-danger"><?= number_format($item['subtotal'], 0, ',', '.') ?>đ</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Tạm tính</span>
                        <strong><?= number_format($total, 0, ',', '.') ?>đ</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Phí vận chuyển</span>
                        <strong><?= number_format($shipping_fee, 0, ',', '.') ?>đ</strong>
                    </div>
                    <div class="d-flex justify-content-between fs-5 fw-bold text-danger">
                        <span>Tổng thanh toán</span>
                        <span><?= number_format($final_total, 0, ',', '.') ?>đ</span>
                    </div>
                </div>
            </div>

            <div class="text-center mt-3">
                <small class="text-muted">
                    <i class="fas fa-shield-alt"></i> Thanh toán khi nhận hàng - Được kiểm tra hàng trước khi thanh toán
                </small>
            </div>
        </div>
    </div>
</div>

<?php include_once "./Views/user/layout_footer.php"; ?>
</body>
</html>