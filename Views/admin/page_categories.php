<?php include_once "./Views/admin/layout_sidebar_admin.php"; ?>

<div class="page-body">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0">Quản lý danh mục</h3>
            <p class="text-muted small mb-0">Phân loại sản phẩm của cửa hàng</p>
        </div>
        <a href="admin.php?ctrl=admin&act=category_add" class="btn btn-primary shadow-soft">
            <i class="bi bi-plus-lg me-1"></i> Thêm mới
        </a>
    </div>

    <div class="card card-box mb-4">
        <div class="card-body">
            <form method="get" class="row g-3">
                <input type="hidden" name="ctrl" value="admin">
                <input type="hidden" name="act" value="categories">
                <div class="col-lg-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="keyword" class="form-control border-start-0 ps-0" 
                               placeholder="Tìm kiếm tên danh mục..." value="<?= htmlspecialchars($keyword ?? '') ?>">
                    </div>
                </div>
                <div class="col-lg-3">
                    <select name="status" class="form-select">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="1" <?= ($status_filter ?? '') === '1' ? 'selected' : '' ?>>Hiển thị</option>
                        <option value="0" <?= ($status_filter ?? '') === '0' ? 'selected' : '' ?>>Ẩn</option>
                    </select>
                </div>
                <div class="col-lg-3">
                    <button type="submit" class="btn btn-dark w-100">Lọc dữ liệu</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-box">
        <div class="card-header-modern d-flex justify-content-between align-items-center">
            <span>Danh sách (<?= $total ?>)</span>
            <small class="text-muted fw-normal">Quản lý phân cấp danh mục</small>
        </div>
        <div class="table-responsive">
            <table class="table table-modern align-middle">
                <thead>
                    <tr>
                        <th width="60">#</th>
                        <th>Tên danh mục</th>
                        <th>Sản phẩm</th>
                        <th>Trạng thái</th>
                        <th>Danh mục cha</th>
                        <th class="text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $stt = $offset + 1;
                    foreach ($displayCategories as $cat):
                        $prefix = $cat['parent_id'] ? '<span class="text-muted me-1">└─</span>' : '';
                        $paddingLeft = $cat['parent_id'] ? 'ps-4' : '';
                    ?>
                    <tr>
                        <td><?= $stt++ ?></td>
                        <td class="<?= $paddingLeft ?>">
                            <span class="fw-bold text-dark"><?= $prefix . htmlspecialchars($cat['name']) ?></span>
                        </td>
                        <td>
                            <span class="badge badge-soft-info">
                                <?= $cat['product_count'] ?> SP
                            </span>
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input toggle-status" type="checkbox" 
                                       <?= $cat['status'] ? 'checked' : '' ?> data-id="<?= $cat['id'] ?>">
                                <label class="form-check-label small ms-2 text-muted">
                                    <?= $cat['status'] ? 'Hiển thị' : 'Ẩn' ?>
                                </label>
                            </div>
                        </td>
                        <td>
                            <?php if($cat['parent_name']): ?>
                                <span class="badge badge-soft-secondary"><?= $cat['parent_name'] ?></span>
                            <?php else: ?>
                                <span class="text-muted small">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <a href="admin.php?ctrl=admin&act=category_edit&id=<?= $cat['id'] ?>" 
                               class="btn btn-sm btn-light border me-1" title="Sửa">
                               <i class="bi bi-pencil-square text-primary"></i>
                            </a>
                            <a href="admin.php?ctrl=admin&act=category_delete&id=<?= $cat['id'] ?>" 
                               onclick="return confirm('Bạn chắc chắn muốn xóa?')" 
                               class="btn btn-sm btn-light border" title="Xóa">
                               <i class="bi bi-trash text-danger"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($totalPages > 1): ?>
        <div class="card-footer bg-white border-top-0 py-3">
            <nav>
                <ul class="pagination justify-content-center mb-0">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link rounded-circle mx-1 text-center" style="width: 32px; height: 32px; padding: 0; line-height: 32px;" 
                               href="?ctrl=admin&act=categories&page=<?= $i ?>&keyword=<?= urlencode($keyword ?? '') ?>&status=<?= $status_filter ?? '' ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.toggle-status').change(function() {
        const id = $(this).data('id');
        $.post('admin.php?ctrl=admin&act=category_toggle_status', { id: id }, function(res) {
            location.reload();
        });
    });
});
</script>