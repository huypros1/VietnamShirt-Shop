
    <main class="py-5">
        <div class="container">
            <div class="row">
                <?php include_once "./Views/user/Layout_user_sidebar.php";?>


                <!-- Nội dung đơn hàng -->
                <div class="col-lg-9 col-md-8">
                    <h4 class="mb-4">Đơn hàng của tôi</h4>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="py-3 ps-4">Mã đơn</th>
                                        <th scope="col" class="py-3">Ngày đặt</th>
                                        <th scope="col" class="py-3">Sản phẩm</th>
                                        <th scope="col" class="py-3">Tổng tiền</th>
                                        <th scope="col" class="py-3">Trạng thái</th>
                                        <th scope="col" class="py-3">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($orders)): ?>
                                        <?php foreach ($orders as $order): ?>
                                            <?php 
                                                // Xử lý hiển thị trạng thái và màu sắc
                                                $statusClass = 'badge bg-secondary';
                                                $statusText = 'Chờ xử lý';
                                                
                                                switch ($order['status']) {
                                                    case 'pending':
                                                        $statusClass = 'badge bg-warning text-dark'; 
                                                        $statusText = 'Chờ xác nhận';
                                                        break;
                                                    case 'shipping':
                                                        $statusClass = 'badge bg-primary';
                                                        $statusText = 'Đang giao';
                                                        break;
                                                    case 'completed':
                                                        $statusClass = 'badge bg-success';
                                                        $statusText = 'Hoàn thành';
                                                        break;
                                                    case 'cancelled':
                                                        $statusClass = 'badge bg-danger';
                                                        $statusText = 'Đã hủy';
                                                        break;
                                                }
                                            ?>
                                            
                                            <tr>
                                                <td class="ps-4 fw-bold">#<?= $order['id'] ?></td>
                                                <td><?= date('d/m/Y', strtotime($order['created_at'])) ?></td>
                                                
                                                <td style="max-width: 300px;">
                                                    <div class="text-truncate" title="<?= $order['product_list'] ?>">
                                                        <?= !empty($order['product_list']) ? $order['product_list'] : 'Chi tiết trong đơn' ?>
                                                    </div>
                                                </td>
                                                
                                                <td class="text-danger fw-bold">
                                                    <?= number_format($order['total_amount'], 0, ',', '.') ?>₫
                                                </td>
                                                
                                                <td>
                                                    <span class="<?= $statusClass ?>">
                                                        <?= $statusText ?>
                                                    </span>
                                                </td>
                                                
                                                <td class="text-nowrap">
                                                    <a href="?ctrl=order&act=detail&id=<?= $order['id'] ?>" 
                                                    class="btn btn-sm btn-outline-dark me-1" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                    </a>

                                                    <?php if ($order['status'] === 'pending'): ?>
                                                        <a href="?ctrl=order&act=cancel&id=<?= $order['id'] ?>" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?');">
                                                        <i class="fas fa-times"></i> Hủy
                                                        </a>
                                                    <?php endif; ?>

                                                    <?php if ($order['status'] === 'completed'): ?>
                                                        <a href="?ctrl=order&act=detail&id=<?= $order['id'] ?>" 
                                                        class="btn btn-sm btn-warning text-dark">
                                                        <i class="fas fa-star"></i> Đánh giá
                                                        </a>
                                                    <?php endif; ?>

                                                    <?php if ($order['status'] === 'cancelled' || $order['status'] === 'completed'): ?>
                                                        <a href="?ctrl=order&act=buyAgain&id=<?= $order['id'] ?>" 
                                                        class="btn btn-sm btn-primary ms-1">
                                                        <i class="fas fa-cart-plus"></i> Mua lại
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Bạn chưa có đơn hàng nào.</p>
                                                <a href="?ctrl=product&act=home" class="btn btn-primary">Mua sắm ngay</a>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-4">
                        <a href="#" class="btn btn-dark">Xem thêm đơn hàng cũ</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    
</body>
</html>