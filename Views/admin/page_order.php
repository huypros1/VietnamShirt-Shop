<?php include_once "./Views/admin/layout_sidebar_admin.php" ?>

<div class="page-body">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0">Quản lý đơn hàng</h3>
            <p class="text-muted small mb-0">Theo dõi và xử lý đơn đặt hàng</p>
        </div>
    </div>

    <div class="card card-box mb-4">
        <div class="card-body">
            <form action="" method="GET" class="row g-3">
                <input type="hidden" name="ctrl" value="admin">
                <input type="hidden" name="act" value="order">
                
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">TRẠNG THÁI</label>
                    <select name="status" class="form-select">
                        <option value="">-- Tất cả --</option>
                        <option value="pending" <?= ($status??'') == 'pending' ? 'selected' : '' ?>>Chờ xử lý</option>
                        <option value="confirmed" <?= ($status??'') == 'confirmed' ? 'selected' : '' ?>>Đã xác nhận</option>
                        <option value="shipping" <?= ($status??'') == 'shipping' ? 'selected' : '' ?>>Đang giao hàng</option>
                        <option value="completed" <?= ($status??'') == 'completed' ? 'selected' : '' ?>>Hoàn thành</option>
                        <option value="cancelled" <?= ($status??'') == 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                    </select>
                </div>

                <div class="col-md-5">
                    <label class="form-label small fw-bold text-muted">TÌM KIẾM</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" name="keyword" value="<?= htmlspecialchars($keyword??'') ?>" class="form-control" placeholder="Mã đơn, tên khách, SĐT...">
                    </div>
                </div>

                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Lọc dữ liệu</button>
                    <a href="?ctrl=admin&act=order" class="btn btn-light border"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-box">
        <div class="card-header-modern d-flex justify-content-between align-items-center">
            <span>Danh sách đơn hàng</span>
            <span class="badge bg-primary rounded-pill"><?= $totalOrders ?? 0 ?> đơn</span>
        </div>
        <div class="table-responsive">
            <table class="table table-modern align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Thanh toán</th>
                        <th>Trạng thái</th>
                        <th class="text-end pe-4">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($orders) && count($orders) > 0): ?>
                        <?php 
                        // Map màu sắc soft-badge
                        $statusMap = [
                            'pending'   => ['badge-soft-warning', 'Chờ xử lý'],
                            'confirmed' => ['badge-soft-info', 'Đã xác nhận'],
                            'shipping'  => ['badge-soft-primary', 'Đang giao'], // Cần thêm class này trong CSS nếu muốn màu xanh đậm hơn, hoặc dùng soft-info
                            'completed' => ['badge-soft-success', 'Hoàn thành'],
                            'cancelled' => ['badge-soft-danger', 'Đã hủy']
                        ];
                        // Tạm fix màu shipping thành info
                        if(!isset($statusMap['shipping'])) $statusMap['shipping'] = ['badge-soft-info text-primary', 'Đang giao'];
                        ?>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold text-primary">#<?= $order['id'] ?></span>
                                </td>
                                
                                <td>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($order['name']) ?></div>
                                    <div class="small text-muted"><?= htmlspecialchars($order['phone']) ?></div>
                                </td>
                                
                                <td>
                                    <?= date('d/m/Y', strtotime($order['created_at'])) ?>
                                    <div class="small text-muted"><?= date('H:i', strtotime($order['created_at'])) ?></div>
                                </td>
                                
                                <td class="fw-bold text-dark">
                                    <?= number_format($order['total_amount'], 0, ',', '.') ?>đ
                                </td>
                                
                                <td>
                                    <?php if ($order['payment_status'] == 'paid'): ?>
                                        <span class="badge badge-soft-success"><i class="bi bi-check2-all"></i> Đã TT</span>
                                    <?php elseif ($order['payment_status'] == 'refunded'): ?>
                                        <span class="badge badge-soft-secondary">Hoàn tiền</span>
                                    <?php else: ?>
                                        <span class="badge badge-soft-warning">Chưa TT</span>
                                    <?php endif; ?>
                                </td>
                                
                                <td>
                                    <?php $st = $statusMap[$order['status']] ?? ['badge-soft-secondary', $order['status']]; ?>
                                    <span class="badge <?= $st[0] ?>"><?= $st[1] ?></span>
                                </td>
                                
                                <td class="text-end pe-4">
                                    <a href="?ctrl=admin&act=order_detail&id=<?= $order['id'] ?>" class="btn btn-sm btn-white border shadow-sm text-primary fw-bold">
                                        Chi tiết <i class="bi bi-arrow-right-short"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-inbox display-4 text-muted opacity-50"></i>
                                <p class="text-muted mt-2">Không tìm thấy đơn hàng nào.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white border-top-0 py-3">
             <nav>
                <ul class="pagination justify-content-center mb-0">
                    <?php for ($i = 1; $i <= ($totalPages ?? 1); $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link rounded-circle mx-1 text-center" style="width: 32px; height: 32px; padding: 0; line-height: 32px;" 
                               href="?ctrl=admin&act=order&page=<?= $i ?>&keyword=<?= $keyword ?>&status=<?= $status ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>