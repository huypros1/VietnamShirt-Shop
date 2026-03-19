<div class="bg-light border-bottom py-3 mb-4">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="?ctrl=page&act=home" class="text-decoration-none text-muted">Trang chủ</a></li>
                <li class="breadcrumb-item active text-dark fw-bold" aria-current="page">Giỏ hàng của bạn</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container mb-5">
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-0 mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-0 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i><?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (empty($cartItems)): ?>
        <div class="text-center py-5 bg-white shadow-sm rounded">
            <img src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/cart/9bdd8040b334d31946f4.png" alt="Empty Cart" style="width: 150px; opacity: 0.5;">
            <h5 class="fw-bold mt-3 text-muted">Giỏ hàng của bạn còn trống</h5>
            <a href="?ctrl=page&act=home" class="btn btn-dark mt-3 px-4 py-2 text-uppercase fw-bold">Mua sắm ngay</a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold m-0">Sản phẩm (<?= $totalItems ?>)</h5>
                    <a href="?ctrl=cart&act=clear" class="text-danger text-decoration-none small fw-bold" onclick="return confirm('Xóa toàn bộ giỏ hàng?')">
                        <i class="bi bi-trash"></i> Xóa tất cả
                    </a>
                </div>

                <div class="bg-white shadow-sm rounded overflow-hidden">
                    <?php foreach ($cartItems as $item): 
                        // Xử lý hiển thị đường dẫn ảnh
                        $imgSrc = "Public/image/" . $item['image'];
                        if (strpos($item['image'], 'image/') === 0) $imgSrc = "Public/" . $item['image'];
                    ?>
                    <div class="p-3 border-bottom cart-item-row position-relative">
                        <div class="row align-items-center">
                            <div class="col-3 col-md-2">
                                <a href="?ctrl=product&act=detail&id=<?= $item['id'] ?>">
                                    <img src="<?= htmlspecialchars($imgSrc) ?>" 
                                         class="img-fluid rounded border" 
                                         style="aspect-ratio: 1/1; object-fit: cover;"
                                         onerror="this.src='Public/image/default.jpg'">
                                </a>
                            </div>
                            
                            <div class="col-9 col-md-5">
                                <a href="?ctrl=product&act=detail&id=<?= $item['id'] ?>" class="text-decoration-none text-dark">
                                    <h6 class="fw-bold mb-1 text-truncate"><?= htmlspecialchars($item['name']) ?></h6>
                                </a>
                                <div class="d-flex gap-2 mt-2">
                                    <span class="badge bg-light text-dark border fw-normal">Size: <?= $item['size'] ?></span>
                                    <span class="badge bg-light text-dark border fw-normal">Màu: <?= $item['color'] ?></span>
                                </div>
                                <div class="mt-2 d-md-none">
                                    <span class="text-danger fw-bold"><?= number_format($item['price'], 0, ',', '.') ?>đ</span>
                                </div>
                            </div>

                            <div class="col-12 col-md-5 mt-3 mt-md-0">
                                <div class="row align-items-center">
                                    <div class="col-4 d-none d-md-block text-center">
                                        <span class="fw-bold"><?= number_format($item['price'], 0, ',', '.') ?>đ</span>
                                    </div>
                                    
                                    <div class="col-8 col-md-6">
                                        <div class="input-group input-group-sm quantity-group">
                                            <a href="?ctrl=cart&act=decrease&id=<?= $item['key'] ?>" class="btn btn-outline-secondary">-</a>
                                            <input type="text" class="form-control text-center bg-white" value="<?= $item['qty'] ?>" readonly>
                                            <?php if($item['qty'] >= $item['max_stock']): ?>
                                                <button class="btn btn-outline-secondary" disabled title="Đã đạt giới hạn kho">+</button>
                                            <?php else: ?>
                                                <a href="?ctrl=cart&act=increase&id=<?= $item['key'] ?>" class="btn btn-outline-secondary">+</a>
                                            <?php endif; ?>
                                        </div>
                                        <?php if($item['qty'] >= $item['max_stock']): ?>
                                            <small class="text-danger d-block mt-1" style="font-size: 11px;">Max kho: <?= $item['max_stock'] ?></small>
                                        <?php endif; ?>
                                    </div>

                                    <div class="col-md-2 text-end d-none d-md-block">
                                        <a href="?ctrl=cart&act=remove&id=<?= $item['key'] ?>" 
                                           class="text-muted hover-danger"
                                           onclick="return confirm('Xóa sản phẩm này?')">
                                            <i class="bi bi-x-circle-fill fs-5"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <a href="?ctrl=cart&act=remove&id=<?= $item['key'] ?>" 
                           class="position-absolute top-0 end-0 m-3 text-muted d-md-none"
                           onclick="return confirm('Xóa sản phẩm này?')">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="mt-3">
                    <a href="?ctrl=page&act=home" class="text-decoration-none text-dark fw-bold">
                        <i class="bi bi-arrow-left me-1"></i> Tiếp tục mua sắm
                    </a>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="bg-white p-4 rounded shadow-sm sticky-top" style="top: 20px; z-index: 1;">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">Cộng giỏ hàng</h5>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tạm tính:</span>
                        <span class="fw-bold"><?= number_format($totalPrice, 0, ',', '.') ?>đ</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Phí vận chuyển:</span>
                        <span class="text-success"><?= ($shippingFee == 0) ? 'Miễn phí' : number_format($shippingFee, 0, ',', '.') . 'đ' ?></span>
                    </div>

                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Mã giảm giá">
                        <button class="btn btn-outline-dark" type="button">Áp dụng</button>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center border-top pt-3 mb-4">
                        <span class="fw-bold fs-5">Tổng cộng:</span>
                        <span class="text-danger fw-bold fs-4"><?= number_format($totalPrice + $shippingFee, 0, ',', '.') ?>đ</span>
                    </div>

                    <a href="?ctrl=order&act=checkout" class="btn btn-danger w-100 py-3 fw-bold text-uppercase shadow-sm">
                        Tiến hành thanh toán
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
    .hover-danger:hover { color: #dc3545 !important; }
    .quantity-group { max-width: 120px; }
    .cart-item-row:last-child { border-bottom: none !important; }
</style>