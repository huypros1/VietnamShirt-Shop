<?php include_once "./Views/admin/layout_sidebar_admin.php" ?>

<div class="container-fluid px-4">
    <h2 class="mt-4">Quản lý đơn hàng</h2>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="?ctrl=admin&act=dashboard">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="?ctrl=admin&act=order">Đơn hàng</a></li>
        <li class="breadcrumb-item active">Chi tiết đơn #<?= $order['id'] ?></li>
    </ol>

    <div class="mb-3">
        <a href="?ctrl=admin&act=order" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Quay lại danh sách
        </a>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class="bi bi-gear-fill me-1"></i> Cập nhật trạng thái
                </div>
                    <div class="card-body">
                        <form action="?ctrl=admin&act=update_order_status" method="POST">
                            <input type="hidden" name="id" value="<?= $order['id'] ?>">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tiến độ đơn hàng:</label>
                                <?php 
                                    // Định nghĩa logic hiển thị (giống trong Controller để đồng bộ)
                                    $currentStatus = $order['status'];
                                    $allowList = [];

                                    switch ($currentStatus) {
                                        case 'pending':   $allowList = ['pending', 'confirmed', 'cancelled']; break;
                                        case 'confirmed': $allowList = ['confirmed', 'shipping', 'cancelled']; break;
                                        case 'shipping':  $allowList = ['shipping', 'completed']; break;
                                        case 'completed': $allowList = ['completed']; break; // Đã xong thì đứng yên
                                        case 'cancelled': $allowList = ['cancelled']; break; // Đã hủy thì đứng yên
                                        default: $allowList = [$currentStatus];
                                    }
                                ?>
                                
                                <select name="status" class="form-select" <?= ($currentStatus == 'completed' || $currentStatus == 'cancelled') ? 'disabled' : '' ?>>
                                    <?php 
                                        $statuses = [
                                            'pending'   => 'Chờ xử lý',
                                            'confirmed' => 'Đã xác nhận',
                                            'shipping'  => 'Đang giao hàng',
                                            'completed' => 'Hoàn thành',
                                            'cancelled' => 'Đã hủy'
                                        ];
                                        foreach($statuses as $key => $label): 
                                            // Kiểm tra xem option này có được phép hiển thị không
                                            $isDisabled = !in_array($key, $allowList);
                                    ?>
                                        <option value="<?= $key ?>" 
                                            <?= ($currentStatus == $key) ? 'selected' : '' ?>
                                            <?= $isDisabled ? 'disabled style="color: #ccc;"' : '' ?>>
                                            <?= $label ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                
                                <?php if($currentStatus == 'completed' || $currentStatus == 'cancelled'): ?>
                                    <input type="hidden" name="status" value="<?= $currentStatus ?>">
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Thanh toán:</label>
                                <select name="payment_status" class="form-select" <?= ($currentStatus == 'completed') ? 'disabled' : '' ?>>
                                    <option value="unpaid" <?= ($order['payment_status'] == 'unpaid') ? 'selected' : '' ?>>Chưa thanh toán</option>
                                    <option value="paid" <?= ($order['payment_status'] == 'paid') ? 'selected' : '' ?>>Đã thanh toán</option>
                                </select>
                                
                                <?php if($currentStatus == 'completed'): ?>
                                    <input type="hidden" name="payment_status" value="paid">
                                <?php endif; ?>
                            </div>

                            <?php if($currentStatus != 'completed' && $currentStatus != 'cancelled'): ?>
                            <button type="submit" class="btn btn-primary w-100 fw-bold">
                                <i class="bi bi-save me-1"></i> Lưu thay đổi
                            </button>
                            <?php else: ?>
                                <div class="alert alert-secondary text-center mb-0">
                                    Đơn hàng đã kết thúc
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>
            </div>

            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-person-bounding-box me-1"></i> Thông tin khách hàng
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><strong>Họ tên:</strong> <?= htmlspecialchars($order['name']) ?></li>
                        <li class="mb-2"><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></li>
                        <li class="mb-2"><strong>SĐT:</strong> <span class="text-primary"><?= htmlspecialchars($order['phone']) ?></span></li>
                        <li class="mb-2"><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['address']) ?></li>
                        <li class="mb-2"><strong>Ngày đặt:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></li>
                    </ul>
                    <?php if(!empty($order['note'])): ?>
                        <div class="alert alert-warning p-2 mt-3 mb-0 small">
                            <i class="bi bi-sticky me-1"></i><strong>Ghi chú:</strong> <?= htmlspecialchars($order['note']) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-bag-check-fill me-1"></i> Danh sách sản phẩm
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Sản phẩm</th>
                                    <th>Phân loại</th>
                                    <th>Đơn giá</th>
                                    <th>SL</th>
                                    <th class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $count = 0; 
                                foreach($items as $item): 
                                    $count++;
                                    // Xử lý hiển thị ảnh
                                    $imgSrc = "Public/image/" . $item['image'];
                                    if (strpos($item['image'], 'image/') === 0) {
                                        $imgSrc = "Public/" . $item['image'];
                                    }
                                    
                                    // Tên sản phẩm
                                    $itemName = $item['product_name'] ?? ($item['name'] ?? 'Sản phẩm không xác định');
                                ?>
                                <tr>
                                    <td><?= $count ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="<?= htmlspecialchars($imgSrc) ?>" 
                                                 class="rounded border me-2" 
                                                 style="width: 45px; height: 45px; object-fit: cover;" 
                                                 onerror="this.src='Public/image/default.jpg'">
                                            <span class="fw-bold text-dark"><?= htmlspecialchars($itemName) ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            <?php if(!empty($item['size'])): ?>
                                                <span class="badge bg-light text-dark border fw-normal">Size: <?= $item['size'] ?></span>
                                            <?php endif; ?>
                                            <?php if(!empty($item['color'])): ?>
                                                <span class="badge bg-light text-dark border fw-normal">Màu: <?= $item['color'] ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td><?= number_format($item['price'], 0, ',', '.') ?>đ</td>
                                    <td class="text-center fw-bold"><?= $item['quantity'] ?></td>
                                    <td class="text-end fw-bold">
                                        <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>đ
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="5" class="text-end">Tạm tính:</td>
                                    <td class="text-end fw-bold"><?= number_format($order['total_amount'] - $order['Shipping_fee'], 0, ',', '.') ?>đ</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-end">Phí vận chuyển:</td>
                                    <td class="text-end fw-bold"><?= number_format($order['Shipping_fee'], 0, ',', '.') ?>đ</td>
                                </tr>
                                <tr class="bg-white">
                                    <td colspan="5" class="text-end fw-bold text-uppercase fs-5">Tổng cộng:</td>
                                    <td class="text-end fw-bold text-danger fs-4"><?= number_format($order['total_amount'], 0, ',', '.') ?>đ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>