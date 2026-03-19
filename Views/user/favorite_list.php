<main class="py-5 bg-light">
    <div class="container">
        <div class="row">
            
            <?php include_once "./Views/user/Layout_user_sidebar.php" ?>

            <div class="col-lg-9 col-md-8">
                <div class="bg-white p-4 shadow-sm rounded mb-4">
                    <h4 class="mb-4 fw-bold border-bottom pb-2">
                        <i class="fas fa-heart text-danger me-2"></i>Sản phẩm yêu thích
                    </h4>

                    <?php if (!empty($favorites)): ?>
                        <div class="row g-4">
                            <?php foreach($favorites as $pro): 
                                // Xử lý đường dẫn ảnh
                                $imgSrc = "Public/image/no-image.jpg";
                                if (!empty($pro['image'])) {
                                    if (strpos($pro['image'], 'image/') === 0) {
                                        $imgSrc = "Public/" . $pro['image'];
                                    } else {
                                        $imgSrc = "Public/image/" . $pro['image'];
                                    }
                                }
                            ?>
                                <div class="col-md-4 col-6">
                                    <div class="card h-100 border-0 shadow-sm rounded overflow-hidden position-relative">
                                        
                                        <a href="?ctrl=product&act=toggle_favorite&id=<?= $pro['id'] ?>&size=<?= $pro['liked_size'] ?>&color=<?= $pro['liked_color'] ?>" 
                                           onclick="return confirm('Xóa khỏi yêu thích?')"
                                           class="position-absolute top-0 end-0 m-2 btn btn-sm btn-light rounded-circle shadow-sm text-danger"
                                           title="Xóa">
                                            <i class="fas fa-times"></i>
                                        </a>

                                        <a href="?ctrl=product&act=detail&id=<?= $pro['id'] ?>&size=<?= $pro['liked_size'] ?>&color=<?= $pro['liked_color'] ?>">
                                            <img src="<?= htmlspecialchars($imgSrc) ?>" 
                                                 class="card-img-top" 
                                                 alt="<?= htmlspecialchars($pro['name']) ?>"
                                                 style="height: 200px; object-fit: contain;">
                                        </a>

                                        <div class="card-body text-center d-flex flex-column">
                                            <h6 class="card-title text-truncate">
                                                <a href="?ctrl=product&act=detail&id=<?= $pro['id'] ?>" class="text-decoration-none text-dark fw-bold">
                                                    <?= htmlspecialchars($pro['name']) ?>
                                                </a>
                                            </h6>
                                            
                                            <div class="mb-2">
                                                <?php if(!empty($pro['liked_size'])): ?>
                                                    <span class="badge bg-light text-dark border me-1"><?= $pro['liked_size'] ?: 'Mặc định' ?></span>  <!-- SỬA: Hiển thị 'Mặc định' nếu rỗng -->
                                                <?php endif; ?>
                                                <?php if(!empty($pro['liked_color'])): ?>
                                                    <span class="badge bg-light text-dark border"><?= $pro['liked_color'] ?: 'Mặc định' ?></span>  <!-- SỬA: Hiển thị 'Mặc định' nếu rỗng -->
                                                <?php endif; ?>
                                            </div>

                                            <p class="card-text text-danger fw-bold mb-3">
                                                <?= number_format($pro['price'], 0, ',', '.') ?> ₫
                                            </p>
                                            
                                            <a href="?ctrl=product&act=detail&id=<?= $pro['id'] ?>&size=<?= $pro['liked_size'] ?>&color=<?= $pro['liked_color'] ?>" class="btn btn-outline-dark btn-sm rounded-pill mt-auto">
                                                Xem chi tiết
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-heart-broken text-muted fa-4x mb-3"></i>
                            <p class="text-muted">Bạn chưa có sản phẩm yêu thích nào.</p>
                            <a href="?ctrl=product&act=list" class="btn btn-dark px-4">Dạo cửa hàng ngay</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
        </div>
    </div>
</main>