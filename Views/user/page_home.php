<?php  
include_once "layout_banner.php";
?>

<section class="category-section py-5">
    <div class="container-fluid px-0">
        <div class="container">
            <div class="row g-3 h-auto">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="category-title">Sản phẩm Bán Chạy</h5>
                    </div>
                    <div class="row product-row g-2"> 
                        <?php if (!empty($bestSellingProducts)): ?>
                            <?php foreach ($bestSellingProducts as $product): ?>
                                <?php 
                                    // XỬ LÝ ẢNH
                                    $hinh = "Public/image/" . $product['image'];
                                    if (strpos($product['image'], 'image/') === 0) {
                                        $hinh = "Public/" . $product['image'];
                                    }
                                ?>
                                <div class="col-lg-2 col-md-3 col-sm-6 product-col"> 
                                    <a href="?ctrl=product&act=detail&id=<?= htmlspecialchars($product['id']) ?>" class="text-decoration-none">
                                        <div class="card product-card h-100 shadow-sm rounded">
                                            <div class="position-relative overflow-hidden">
                                                <img src="<?= $hinh ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>" style="height: 280px; object-fit: cover;">
                                                <span class="badge bg-danger price-badge">
                                                    <?= !empty($product['price']) ? number_format($product['price'], 0, ',', '.') . 'Đ' : 'Liên hệ' ?>
                                                </span>
                                            </div>
                                            <div class="card-body p-2">
                                                <h6 class="card-title small mb-1 text-truncate"><?= htmlspecialchars($product['name']) ?></h6>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <p class="small text-muted mb-0">Đã bán: <?= $product['sold'] ?? 0 ?></p>  
                                                    <a href="?ctrl=product&act=detail&id=<?= $product['id'] ?>">
                                                        <button class="btn btn-sm btn-outline-danger rounded-circle">
                                                                <i class="fas fa-shopping-cart"></i>
                                                        </button>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="col-12 text-center">Đang cập nhật sản phẩm bán chạy.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="category-section py-5">
    <div class="container-fluid px-0">
        <div class="container">
            <div class="row g-3 h-auto">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="category-title">Sản phẩm Mới</h5>
                    </div>
                    <div class="row product-row g-2"> 
                        <?php if (!empty($newProducts)): ?>
                            <?php foreach ($newProducts as $product): ?>
                                <?php 
                                    // XỬ LÝ ẢNH
                                    $hinh = "Public/image/" . $product['image'];
                                    if (strpos($product['image'], 'image/') === 0) {
                                        $hinh = "Public/" . $product['image'];
                                    }
                                ?>
                                <div class="col-lg-2 col-md-3 col-sm-6 product-col"> 
                                    <a href="?ctrl=product&act=detail&id=<?= htmlspecialchars($product['id']) ?>" class="text-decoration-none">
                                        <div class="card product-card h-100 shadow-sm rounded">
                                            <div class="position-relative overflow-hidden">
                                                <img src="<?= $hinh ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>" style="height: 280px; object-fit: cover;">
                                                <span class="badge bg-danger price-badge">
                                                    <?= !empty($product['price']) ? number_format($product['price'], 0, ',', '.') . 'Đ' : 'Liên hệ' ?>
                                                </span>
                                            </div>
                                            <div class="card-body p-2">
                                                <h6 class="card-title small mb-1 text-truncate"><?= htmlspecialchars($product['name']) ?></h6>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <p class="small text-muted mb-0">Đã bán: <?= $product['sold'] ?? 0 ?></p>  
                                                    <a href="?ctrl=product&act=detail&id=<?= $product['id'] ?>">
                                                        <button class="btn btn-sm btn-outline-danger rounded-circle">
                                                                <i class="fas fa-shopping-cart"></i>
                                                        </button>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="col-12 text-center">Đang cập nhật sản phẩm mới.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="category-section py-5">
    <div class="container-fluid px-0">
        <div class="container">
            <div class="row g-3 h-auto">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="category-title">Sản phẩm Dành Cho Bạn</h5>
                    </div>
                    <div class="row product-row g-2"> 
                        <?php if (!empty($recommendedProducts)): ?>
                            <?php foreach ($recommendedProducts as $product): ?>
                                <?php 
                                    // XỬ LÝ ẢNH
                                    $hinh = "Public/image/" . $product['image'];
                                    if (strpos($product['image'], 'image/') === 0) {
                                        $hinh = "Public/" . $product['image'];
                                    }
                                ?>
                                <div class="col-lg-2 col-md-3 col-sm-6 product-col"> 
                                    <a href="?ctrl=product&act=detail&id=<?= htmlspecialchars($product['id']) ?>" class="text-decoration-none">
                                        <div class="card product-card h-100 shadow-sm rounded">
                                            <div class="position-relative overflow-hidden">
                                                <img src="<?= $hinh ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>" style="height: 280px; object-fit: cover;">
                                                <span class="badge bg-danger price-badge">
                                                    <?= !empty($product['price']) ? number_format($product['price'], 0, ',', '.') . 'Đ' : 'Liên hệ' ?>
                                                </span>
                                            </div>
                                            <div class="card-body p-2">
                                                <h6 class="card-title small mb-1 text-truncate"><?= htmlspecialchars($product['name']) ?></h6>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <p class="small text-muted mb-0">Đã bán: <?= $product['sold'] ?? 0 ?></p>  
                                                    <a href="?ctrl=product&act=detail&id=<?= $product['id'] ?>">
                                                        <button class="btn btn-sm btn-outline-danger rounded-circle">
                                                                <i class="fas fa-shopping-cart"></i>
                                                        </button>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="col-12 text-center">Đang cập nhật sản phẩm đề xuất.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="category-section py-5">
    <div class="container-fluid px-0">
        <div class="container">
            <div class="row g-3 h-auto">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="category-title">Tin Tức</h5>

                    </div>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="card h-100">
                                <img src="https://4men.com.vn/images/thumbs/2017/08/nhung-mau-ao-thun-nam-dep-nhat-hien-nay-tai-4men-news-237.jpg" class="card-img-top" alt="Tin tức 1">
                                <div class="card-body">
                                    <h6 class="card-title mb-2">Khuyến mãi mùa đông: Giảm 20% áo len</h6>
                                    <p class="small text-muted mb-3">Từ ngày 1/11 đến 30/11, tất cả áo len giảm giá 20%. Đừng bỏ lỡ!</p>
                                    <a href="#" class="text-danger text-decoration-underline">Đọc thêm</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100">
                                <img src="https://4men.com.vn/images/catalogues/ao-khoac-du-nam.png" class="card-img-top" alt="Tin tức 2">
                                <div class="card-body">
                                    <h6 class="card-title mb-2">Sản phẩm mới: Bộ sưu tập áo polo 2024</h6>
                                    <p class="small text-muted mb-3">Giới thiệu bộ sưu tập áo polo mới với chất liệu cao cấp.</p>
                                    <a href="#" class="text-danger text-decoration-underline">Đọc thêm</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100">
                                <img src="https://thuthuatnhanh.com/wp-content/uploads/2022/08/ao-thun-in-hinh-theo-yeu-cau.jpg" class="card-img-top" alt="Tin tức 3">
                                <div class="card-body">
                                    <h6 class="card-title mb-2">Hướng dẫn phối đồ với áo sơ mi</h6>
                                    <p class="small text-muted mb-3">Mẹo phối đồ để bạn luôn phong cách với áo sơ mi của chúng tôi.</p>
                                    <a href="#" class="text-danger text-decoration-underline">Đọc thêm</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="testimonials py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="category-title">Khách Hàng Nói Gì Về Chúng Tôi</h5>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center p-4">
                    <div class="mb-3">
                        <img src="https://i.pravatar.cc/150?img=1" class="rounded-circle shadow-sm" width="80" height="80" alt="User">
                    </div>
                    <div class="text-warning mb-3">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <p class="text-muted fst-italic">"Sản phẩm rất đẹp, chất vải sờ rất thích. Giao hàng nhanh hơn mình nghĩ, đóng gói cẩn thận. Sẽ ủng hộ shop dài dài!"</p>
                    <h6 class="fw-bold mt-auto">- Nguyễn Văn A</h6>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center p-4">
                    <div class="mb-3">
                        <img src="https://i.pravatar.cc/150?img=5" class="rounded-circle shadow-sm" width="80" height="80" alt="User">
                    </div>
                    <div class="text-warning mb-3">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                    </div>
                    <p class="text-muted fst-italic">"Nhân viên tư vấn nhiệt tình, chọn size chuẩn không cần chỉnh. Giá cả hợp lý so với chất lượng."</p>
                    <h6 class="fw-bold mt-auto">- Trần Thị B</h6>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center p-4">
                    <div class="mb-3">
                        <img src="https://i.pravatar.cc/150?img=8" class="rounded-circle shadow-sm" width="80" height="80" alt="User">
                    </div>
                    <div class="text-warning mb-3">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <p class="text-muted fst-italic">"Mình mua tặng người yêu mà ổng thích mê. Shop còn tặng kèm thiệp rất dễ thương. 10 điểm!"</p>
                    <h6 class="fw-bold mt-auto">- Lê Hoàng C</h6>
                </div>
            </div>
        </div>
    </div>
</section>
    
<section class="newsletter py-5 bg-dark text-white">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <i class="far fa-envelope fa-3x mb-3 text-danger"></i>
                <h3 class="fw-bold">Đăng Ký Nhận Tin</h3>
                <p class="text-white-50 mb-4">Nhận thông tin về sản phẩm mới và mã giảm giá 20% cho đơn hàng đầu tiên.</p>
                <form action="#" class="position-relative">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control rounded-pill py-3 px-4" placeholder="Nhập địa chỉ email của bạn..." aria-label="Email">
                        <button class="btn btn-danger rounded-pill px-4 position-absolute end-0 top-0 h-100 z-1 m-0" type="button">Đăng Ký</button>
                    </div>
                </form>
                <small class="text-white-50">Chúng tôi cam kết không spam email của bạn.</small>
            </div>
        </div>
    </div>
</section>

</body>
</html>