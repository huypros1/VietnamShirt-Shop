<main class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Chi tiết đơn hàng #<?= $order['id'] ?></h3>
            <a href="?ctrl=user&act=myOrders" class="btn btn-outline-dark">
                <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
            </a>
        </div>

        <?php if (!empty($message_success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> <?= $message_success ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (!empty($message_error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> <?= $message_error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

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
                                        <th>Phân loại</th> 
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
                                        
                                        $imgSrc = "Public/image/" . $item['image'];
                                        if (strpos($item['image'], 'image/') === 0) $imgSrc = "Public/" . $item['image'];
                                        $itemName = $item['product_name'] ?? ($item['name'] ?? 'Sản phẩm');
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="<?= htmlspecialchars($imgSrc) ?>" style="width: 60px; height: 60px; object-fit: cover;" class="rounded me-3 border">
                                                <h6 class="mb-0 fw-bold"><?= htmlspecialchars($itemName) ?></h6>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                <?php if(!empty($item['size'])): ?> <small class="text-muted">Size: <?= $item['size'] ?></small> <?php endif; ?>
                                                <?php if(!empty($item['color'])): ?> <small class="text-muted">Màu: <?= $item['color'] ?></small> <?php endif; ?>
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

                <?php if ($order['status'] === 'completed'): ?>
                    <div class="card border-0 shadow-sm mb-4" id="review-section">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-star me-2"></i>Đánh giá sản phẩm</h5>
                        </div>
                        <div class="card-body">
                            <form method="post" action="?ctrl=order&act=detail&id=<?= $order['id'] ?>#review-section">
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Chọn sản phẩm muốn đánh giá:</label>
                                    <select name="product_id_review" class="form-select" required>
                                        <?php 
                                        // Mảng tạm để lọc trùng sản phẩm (nếu mua nhiều biến thể của 1 sp)
                                        $displayed_products = [];
                                        foreach ($items as $itm): 
                                            $pid = $itm['product_id'] ?? $itm['id']; // Lấy ID gốc của sản phẩm
                                            if (in_array($pid, $displayed_products)) continue; // Nếu đã hiện rồi thì bỏ qua
                                            
                                            $displayed_products[] = $pid;
                                            $pname = $itm['product_name'] ?? $itm['name'];
                                        ?>
                                            <option value="<?= $pid ?>">
                                                <?= htmlspecialchars($pname) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3 text-center">
                                    <label class="form-label fw-bold d-block">Mức độ hài lòng:</label>
                                    
                                    <div class="rating-group">
                                        <input type="radio" id="star5" name="rating" value="5" checked />
                                        <label for="star5" class="fa fa-star" title="Tuyệt vời"></label>
                                        
                                        <input type="radio" id="star4" name="rating" value="4" />
                                        <label for="star4" class="fa fa-star" title="Tốt"></label>
                                        
                                        <input type="radio" id="star3" name="rating" value="3" />
                                        <label for="star3" class="fa fa-star" title="Bình thường"></label>
                                        
                                        <input type="radio" id="star2" name="rating" value="2" />
                                        <label for="star2" class="fa fa-star" title="Tệ"></label>
                                        
                                        <input type="radio" id="star1" name="rating" value="1" />
                                        <label for="star1" class="fa fa-star" title="Rất tệ"></label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nội dung đánh giá:</label>
                                    <textarea name="content" class="form-control" rows="3" placeholder="Sản phẩm dùng có tốt không? Chất lượng thế nào?" required></textarea>
                                </div>

                                <button type="submit" name="submit_review" class="btn btn-primary w-100">
                                    <i class="fas fa-paper-plane me-2"></i> Gửi đánh giá
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php endif; ?>

            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Thanh toán</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tiền hàng:</span>
                            <span><?= number_format($total_product_price, 0, ',', '.') ?>₫</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Phí vận chuyển:</span>
                            <span><?= number_format($order['Shipping_fee'], 0, ',', '.') ?>₫</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">Tổng cộng:</span>
                            <span class="fw-bold text-danger fs-5"><?= number_format($order['total_amount'], 0, ',', '.') ?>₫</span>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Thông tin nhận hàng</h5>
                        <p class="mb-2"><i class="fas fa-user me-2 text-muted"></i> <?= htmlspecialchars($order['name']) ?></p>
                        <p class="mb-2"><i class="fas fa-phone me-2 text-muted"></i> <?= htmlspecialchars($order['phone']) ?></p>
                        <p class="mb-2"><i class="fas fa-map-marker-alt me-2 text-muted"></i> <?= htmlspecialchars($order['address']) ?></p>
                        
                        <div class="mt-3 pt-3 border-top">
                            <strong>Trạng thái:</strong> 
                            <?php
                                $statusMap = [
                                    'pending'   => ['bg-warning text-dark', 'Chờ xác nhận'],
                                    'shipping'  => ['bg-primary', 'Đang giao hàng'],
                                    'completed' => ['bg-success', 'Giao thành công'],
                                    'cancelled' => ['bg-danger', 'Đã hủy']
                                ];
                                $st = $statusMap[$order['status']] ?? ['bg-secondary', $order['status']];
                            ?>
                            <span class="badge <?= $st[0] ?>"><?= $st[1] ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<style>
    /* Ẩn input radio */
    .rating-group input {
        display: none;
    }
    
    /* Căn giữa và đảo ngược thứ tự flex để dùng selector ~ */
    .rating-group {
        display: inline-flex;
        flex-direction: row-reverse; 
        justify-content: flex-end;
    }

    /* Style cho ngôi sao chưa chọn (màu xám) */
    .rating-group label {
        font-size: 30px;
        color: #ccc;
        cursor: pointer;
        transition: color 0.2s;
        padding: 0 5px;
    }

    /* Hiệu ứng: Khi hover vào 1 sao, các sao phía sau nó (về mặt DOM là phía trước về mặt hiển thị) sẽ sáng */
    .rating-group label:hover,
    .rating-group label:hover ~ label,
    .rating-group input:checked ~ label {
        color: #ffc107; /* Màu vàng */
    }
    
    /* Khi hover, ngôi sao đang hover sẽ đậm hơn chút */
    .rating-group label:hover {
        transform: scale(1.1);
    }
</style>