<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .btn-size { min-width: 50px; margin: 5px 5px 5px 0; border-radius: 4px; }
        .btn-size.active { background-color: #000; color: #fff; border-color: #000; }
        .btn-size:hover { border-color: #000; }
        .quantity-wrapper { border: 1px solid #dee2e6; border-radius: 4px; display: inline-flex; align-items: center; }
        .quantity-btn { width: 35px; height: 35px; background: #fff; border: none; font-weight: bold; cursor: pointer; }
        .quantity-btn:hover { background: #f8f9fa; }
        .quantity-input { width: 50px; text-align: center; border: none; font-weight: bold; outline: none; }
        .btn-love { width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; transition: 0.2s; border-radius: 8px; }
        .btn-love:hover { background-color: #fff0f1; border-color: #ff4d4f; color: #ff4d4f; }
        .thumb-btn { transition: all 0.2s; }
        .thumb-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .qa-avatar { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; color: white; font-size: 14px; flex-shrink: 0; }
        .bg-user { background-color: #6c757d; }
        .bg-admin { background-color: #d70018; }
        .badge-qtv { background-color: #d70018; color: white; font-size: 10px; padding: 2px 6px; border-radius: 4px; margin-left: 5px; vertical-align: middle; font-weight: normal; }
        .reply-link { color: #d70018; font-size: 13px; font-weight: 600; text-decoration: none; cursor: pointer; margin-top: 5px; display: inline-block; }
        .reply-link:hover { text-decoration: underline; }
        .reply-container { background: #f8f9fa; border-radius: 8px; padding: 15px; margin-top: 10px; margin-left: 40px; position: relative; }
        .reply-container::before { content: ""; position: absolute; top: -10px; left: 20px; border-width: 0 10px 10px 10px; border-style: solid; border-color: transparent transparent #f8f9fa transparent; }
        .qa-time { font-size: 12px; color: #999; margin-left: auto; }
        .qa-content { font-size: 14px; margin-top: 4px; line-height: 1.5; color: #333; }
        .qa-name { font-size: 14px; font-weight: 700; color: #333; }
    </style>
</head>
<body class="bg-light">

<?php if(isset($_SESSION['swal'])): ?>
    <script>
        Swal.fire({
            icon: '<?= $_SESSION['swal']['type'] ?>',
            title: '<?= $_SESSION['swal']['title'] ?>',
            text: '<?= $_SESSION['swal']['text'] ?>',
            confirmButtonColor: '#d70018',
            timer: 3000
        });
    </script>
    <?php unset($_SESSION['swal']); ?>
<?php endif; ?>

<div class="container py-5">
    
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="?ctrl=product&act=list" class="text-decoration-none text-muted">Sản phẩm</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($product['name']) ?></li>
        </ol>
    </nav>

    <div class="row g-5">
        <div class="col-md-5">
            <?php 
                function fixImgPath($path) {
                     if (empty($path)) return "Public/image/no-image.jpg"; 
                     if (strpos($path, 'image/') === 0) return "Public/" . $path;
                     return "Public/image/" . $path;
                }
                $hinhChinh = fixImgPath($current_main_image);

                // LOGIC KHẮC PHỤC: Buộc chọn màu Trắng nếu sản phẩm là màu trắng và chưa có lựa chọn màu nào được gửi (tránh ghi đè khi người dùng đã chọn)
                if (isset($product['name']) && strpos(strtolower($product['name']), 'trắng') !== false && !isset($_POST['color']) && isset($ds_mau) && in_array('Trắng', $ds_mau)) {
                    $color = 'Trắng';
                }
            ?>

            <form method="post" action="">
                <input type="hidden" name="old_size" value="<?= $size ?>">
                <input type="hidden" name="old_color" value="<?= $color ?>">
                <input type="hidden" name="quantity" value="<?= $sl ?>">

                <div class="bg-white p-3 rounded shadow-sm text-center mb-3">
                    <img src="<?= htmlspecialchars($hinhChinh) ?>" 
                         class="img-fluid" style="max-height:500px; object-fit:contain;">
                </div>

                <?php if (count($all_images) > 1): ?>
                    <div class="row g-2 justify-content-center">
                        <?php 
                        $thumbs = array_slice($all_images, 0, 4); 
                        foreach($thumbs as $img): 
                            $hinhThumb = fixImgPath($img['image_path']);
                            $isActive = ($img['image_path'] == $current_main_image) ? 'border-primary border-2' : '';
                        ?>
                        <div class="col-3">
                            <button type="submit" name="chon_anh" value="<?= $img['image_path'] ?>" 
                                    class="btn p-0 w-100 border shadow-sm thumb-btn <?= $isActive ?>" 
                                    style="overflow: hidden; height: 120px;">
                                <img src="<?= htmlspecialchars($hinhThumb) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            </button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </form>
        </div>

        <div class="col-md-7">
            <h1 class="fw-bold mb-2"><?= htmlspecialchars($product['name']) ?></h1>
            
            <div class="d-flex align-items-center mb-3">
                <span class="text-muted small"><i class="bi bi-bag-check-fill me-1"></i>Đã bán: <?= $product['sold'] ?? 0 ?></span>
            </div>
            
            <h2 class="text-danger fw-bold mb-4">
                <?php if($gia > 0): ?>
                    <?= number_format($gia, 0, ',', '.') ?> ₫
                <?php else: ?>
                    <span class="text-muted fs-4">Liên hệ / Hết hàng</span>
                <?php endif; ?>
            </h2>

            <form method="post" action="">
                <input type="hidden" name="old_size" value="<?= $size ?>">
                <input type="hidden" name="old_color" value="<?= $color ?>">

                <div class="mb-4">
                    <strong class="d-block mb-2">Kích thước:</strong>
                    <?php if(!empty($ds_size)): ?>
                        <?php foreach($ds_size as $s): ?>
                            <button type="submit" name="size" value="<?= $s ?>" 
                                    class="btn btn-outline-dark btn-size <?= ($s == $size) ? 'active' : '' ?>">
                                <?= $s ?>
                            </button>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-danger small">Tạm hết hàng.</p>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <strong class="d-block mb-2">Màu sắc:</strong>
                    <?php 
                        // SẮP XẾP MÀU: Đưa màu "Trắng" lên đầu nếu có
                        if (($key = array_search('Trắng', $ds_mau)) !== false) {
                            unset($ds_mau[$key]);
                            array_unshift($ds_mau, 'Trắng');
                        }
                    ?>
                    <?php foreach($ds_mau as $m): ?>
                        <button type="submit" name="color" value="<?= $m ?>" 
                                class="btn btn-outline-dark btn-size <?= ($m == $color) ? 'active' : '' ?>">
                            <?= $m ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <div class="mb-4">
                    <strong class="d-block mb-2">Số lượng:</strong>
                    <div class="d-flex align-items-center">
                        <div class="quantity-wrapper me-3">
                            <button type="submit" name="giam" class="quantity-btn" <?= $sl<=1?'disabled':'' ?>>-</button>
                            <input type="text" name="sl" value="<?= $sl ?>" class="quantity-input" readonly>
                            <button type="submit" name="tang" class="quantity-btn" <?= ($stock == 0 || $sl >= $stock)?'disabled':'' ?>>+</button>
                        </div>
                        <span class="text-muted small">Kho: <strong class="text-danger"><?= $stock ?></strong> sản phẩm</span>
                    </div>
                </div>
            </form>

            <form method="post" action="?ctrl=cart&act=add&id=<?= $product['id'] ?>">
                <input type="hidden" name="size"  value="<?= $size ?>">
                <input type="hidden" name="color" value="<?= $color ?>">
                <input type="hidden" name="price" value="<?= $gia ?>">
                <input type="hidden" name="sl"    value="<?= $sl ?>">

                <div class="d-flex gap-3 mt-4">
                    <button type="submit" name="add_to_cart" value="1" class="btn btn-outline-primary btn-lg flex-grow-1" <?= ($stock <= 0) ? 'disabled' : '' ?>>
                        <i class="bi bi-cart-plus me-2"></i> Thêm giỏ hàng
                    </button>
                    <button type="submit" name="buy_now" value="1" class="btn btn-danger btn-lg flex-grow-1" <?= ($stock <= 0) ? 'disabled' : '' ?>>
                        Mua ngay
                    </button>
                    <button type="submit" formaction="?ctrl=product&act=toggle_favorite&id=<?= $product['id'] ?>"
                            class="btn btn-outline-danger btn-love border">
                        <i class="bi <?= (isset($isFavorite) && $isFavorite) ? 'bi-heart-fill' : 'bi-heart' ?>"></i>
                    </button>
                </div>
            </form>

        </div> 
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <div class="bg-white p-4 rounded shadow-sm">
                <h5 class="fw-bold mb-3 text-uppercase border-bottom pb-2">Mô tả chi tiết</h5>
                <div class="text-dark text-break">
                    <?php if (!empty($product['description'])) { echo nl2br(htmlspecialchars($product['description'])); } else { echo '<p class="text-muted fst-italic">Thông tin mô tả sản phẩm đang được cập nhật...</p>'; } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <div class="bg-white p-4 rounded shadow-sm">
                <h5 class="fw-bold mb-3 text-uppercase border-bottom pb-2">Bảng quy đổi kích cỡ</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center mb-0"><thead class="table-dark"><tr><th class="py-3">SIZE</th><th class="py-3">CÂN NẶNG (KG)</th><th class="py-3">CHIỀU CAO (CM)</th></tr></thead><tbody><?php if (!empty($bang_size)): foreach ($bang_size as $row): ?><tr><td class="fw-bold text-primary align-middle fs-5"><?= $row['size_name'] ?></td><td class="align-middle"><?= $row['weight_min'] ?> - <?= $row['weight_max'] ?></td><td class="align-middle"><?= $row['height_min'] ?> - <?= $row['height_max'] ?></td></tr><?php endforeach; else: ?><tr><td colspan="3" class="p-3 text-muted">Đang cập nhật dữ liệu...</td></tr><?php endif; ?></tbody></table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <h4 class="fw-bold mb-4 text-uppercase border-bottom pb-2">Đánh giá sản phẩm</h4>
            <div class="row">
                <div class="col-md-12">
                    <div class="bg-white p-4 rounded shadow-sm">
                        <?php if (!empty($list_comments)): foreach($list_comments as $cmt): ?><div class="d-flex mb-4 border-bottom pb-3"><div class="flex-shrink-0"><div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 50px; height: 50px;"><?= strtoupper(substr($cmt['user_name'], 0, 1)) ?></div></div><div class="ms-3 w-100"><div class="d-flex justify-content-between"><h6 class="fw-bold mb-0"><?= htmlspecialchars($cmt['user_name']) ?></h6><span class="text-muted small"><?= date('d/m/Y', strtotime($cmt['created_at'])) ?></span></div><div class="text-warning small mb-1"><?php for($i=1; $i<=5; $i++) echo ($i <= $cmt['rating']) ? '★' : '☆'; ?></div><p class="mb-0 text-dark"><?= nl2br(htmlspecialchars($cmt['content'])) ?></p></div></div><?php endforeach; else: ?><p class="text-muted text-center py-4">Chưa có đánh giá nào.</p><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12 mb-4"><h3 class="fw-bold text-uppercase border-bottom pb-2">Có thể bạn sẽ thích</h3></div>
        <?php if (!empty($related_products)): foreach($related_products as $rp): $hinhRP = "Public/image/" . $rp['image']; if (strpos($rp['image'], 'image/') === 0) $hinhRP = "Public/" . $rp['image']; ?>
            <div class="col-md-3 col-6 mb-4"><div class="card h-100 border-0 shadow-sm"><a href="?ctrl=product&act=detail&id=<?= $rp['id'] ?>" class="overflow-hidden"><img src="<?= htmlspecialchars($hinhRP) ?>" class="card-img-top transition-transform" style="object-fit:cover; height:250px;"></a><div class="card-body text-center"><h6 class="card-title text-truncate"><a href="?ctrl=product&act=detail&id=<?= $rp['id'] ?>" class="text-decoration-none text-dark fw-bold"><?= htmlspecialchars($rp['name']) ?></a></h6><p class="card-text text-danger fw-bold"><?= !empty($rp['price']) ? number_format($rp['price'],0,',','.') . ' ₫' : 'Liên hệ' ?></p></div></div></div>
        <?php endforeach; else: ?><div class="col-12 text-center text-muted"><p>Không có sản phẩm liên quan nào.</p></div><?php endif; ?>
    </div>

    <div class="row mt-5 mb-5">
        <div class="col-12 mb-4"><h3 class="fw-bold text-uppercase border-bottom pb-2">Hỏi đáp về sản phẩm</h3></div>
        <div class="col-12 mb-4"><div class="card bg-light border-0 shadow-sm"><div class="card-body p-4"><h6 class="fw-bold mb-3">Đặt câu hỏi cho Shop</h6><?php if(isset($_SESSION['user'])): ?><form method="post" action=""><div class="row"><div class="col-md-10 mb-2"><textarea name="question_content" class="form-control" rows="2" placeholder="Nội dung câu hỏi..." required></textarea></div><div class="col-md-2 d-grid"><button type="submit" name="submit_question" class="btn btn-danger text-white fw-bold"><i class="bi bi-send-fill me-1"></i> Gửi</button></div></div></form><?php else: ?><div class="alert alert-warning py-2 mb-0">Vui lòng <a href="?ctrl=user&act=login" class="fw-bold text-dark text-decoration-underline">đăng nhập</a> để đặt câu hỏi.</div><?php endif; ?></div></div></div>
        <div class="col-12">
            <div class="bg-white p-4 rounded shadow-sm border">
                <h5 class="fw-bold mb-4">Các câu hỏi thường gặp</h5>
                <?php if (!empty($list_questions)): foreach($list_questions as $qa): ?>
                    <div class="mb-4 pb-3 border-bottom">
                        <div class="d-flex gap-3">
                            <div class="qa-avatar bg-user"><?= strtoupper(substr($qa['user_name'], 0, 1)) ?></div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center mb-1">
                                    <strong class="qa-name me-2"><?= htmlspecialchars($qa['user_name']) ?></strong>
                                    <span class="qa-time"><i class="bi bi-clock me-1"></i><?= date('d/m/Y H:i', strtotime($qa['created_at'])) ?></span>
                                </div>
                                <div class="qa-content"><?= nl2br(htmlspecialchars($qa['content'])) ?></div>
                                
                                <?php if(isset($_SESSION['user'])): ?>
                                    <a class="reply-link" data-bs-toggle="collapse" href="#replyBox<?= $qa['id'] ?>" role="button" aria-expanded="false">
                                        <i class="bi bi-chat-dots-fill me-1"></i>Trả lời
                                    </a>
                                <?php endif; ?>

                                <div class="mt-2">
                                    <?php if (!empty($qa['reply'])): ?>
                                        <div class="reply-container">
                                            <div class="d-flex gap-2">
                                                <div class="qa-avatar bg-admin">Q</div>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex align-items-center mb-1"><strong class="qa-name text-danger">Quản Trị Viên <span class="badge-qtv">QTV</span></strong></div>
                                                    <div class="qa-content"><?= nl2br(htmlspecialchars($qa['reply'])) ?></div>
                                                    <?php if(isset($_SESSION['user'])): ?>
                                                        <a class="reply-link" data-bs-toggle="collapse" href="#replyBox<?= $qa['id'] ?>" role="button" aria-expanded="false"><i class="bi bi-chat-dots-fill me-1"></i> Trả lời</a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php 
                                        $replies = (new Product())->getQuestionReplies($qa['id']); 
                                        if(!empty($replies)): foreach($replies as $rep): 
                                            $isAdmin = ($rep['role'] == 'admin'); 
                                    ?>
                                        <div class="reply-container">
                                            <div class="d-flex gap-2">
                                                <?php if($isAdmin): ?>
                                                    <div class="qa-avatar bg-admin">Q</div>
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center mb-1"><strong class="qa-name text-danger"><?= htmlspecialchars($rep['user_name']) ?> <span class="badge-qtv">QTV</span></strong><span class="qa-time"><?= date('d/m H:i', strtotime($rep['created_at'])) ?></span></div>
                                                        <div class="qa-content"><?= nl2br(htmlspecialchars($rep['content'])) ?></div>
                                                        <?php if(isset($_SESSION['user'])): ?>
                                                            <a class="reply-link" data-bs-toggle="collapse" href="#replyBox<?= $qa['id'] ?>" role="button" aria-expanded="false"><i class="bi bi-chat-dots-fill me-1"></i> Trả lời</a>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="qa-avatar bg-user"><?= strtoupper(substr($rep['user_name'], 0, 1)) ?></div>
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center mb-1"><strong class="qa-name"><?= htmlspecialchars($rep['user_name']) ?></strong><span class="qa-time"><?= date('d/m H:i', strtotime($rep['created_at'])) ?></span></div>
                                                        <div class="qa-content"><?= nl2br(htmlspecialchars($rep['content'])) ?></div>
                                                        <?php if(isset($_SESSION['user'])): ?>
                                                            <a class="reply-link" data-bs-toggle="collapse" href="#replyBox<?= $qa['id'] ?>" role="button" aria-expanded="false"><i class="bi bi-chat-dots-fill me-1"></i> Trả lời</a>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; endif; ?>
                                </div>

                                <?php if(isset($_SESSION['user'])): ?>
                                    <div class="collapse mt-3" id="replyBox<?= $qa['id'] ?>">
                                        <form method="post" action="" class="p-3 bg-light rounded border">
                                            <input type="hidden" name="parent_id" value="<?= $qa['id'] ?>">
                                            <div class="mb-2"><textarea name="user_reply_content" class="form-control form-control-sm" rows="3" placeholder="Nhập nội dung thảo luận..." required></textarea></div>
                                            <div class="text-end"><button type="submit" name="submit_user_reply" class="btn btn-sm btn-danger fw-bold">Gửi ngay</button></div>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; else: ?>
                    <div class="text-center py-5 text-muted"><i class="bi bi-chat-square-text display-4 mb-3 d-block"></i><p>Hiện chưa có câu hỏi nào.</p></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div> 

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>