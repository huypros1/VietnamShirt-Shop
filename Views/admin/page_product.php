<?php include_once "./Views/admin/layout_sidebar_admin.php" ?>

<style>
    /* CSS TOÀN TRANG */
    body { background-color: #f8f9fa; }
    
    /* Card Box */
    .card-box { border: none; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); background: #fff; }
    
    /* Table Modern */
    .table-modern thead th { 
        background-color: #f8f9fa; color: #6c757d; font-weight: 600; font-size: 0.75rem; 
        text-transform: uppercase; border: none; padding: 14px 20px; 
    }
    .table-modern tbody tr { border-bottom: 1px solid #f1f3f5; transition: background 0.2s; }
    .table-modern tbody tr:hover { background-color: #fdfdfe; }
    .table-modern td { padding: 12px 20px; vertical-align: middle; }
    
    /* Ảnh Thumbnail */
    .img-thumb { width: 56px; height: 56px; border-radius: 10px; object-fit: cover; border: 1px solid #eee; }
    
    /* Badge trạng thái */
    .badge-soft-success { background-color: #d1e7dd; color: #0f5132; }
    .badge-soft-warning { background-color: #fff3cd; color: #664d03; }
    .badge-soft-danger { background-color: #f8d7da; color: #842029; }
    .badge-soft-secondary { background-color: #e2e3e5; color: #41464b; }
    
    /* Filter Select */
    .filter-select { border-radius: 50px; border: 1px solid #dee2e6; padding-left: 15px; background-position: right 1rem center; }

    /* Dòng bị Ẩn (Status = 0) */
    .row-hidden { background-color: #f3f4f6 !important; }
    .row-hidden td { color: #adb5bd; }
    .row-hidden img { filter: grayscale(100%); opacity: 0.6; }
    .row-hidden .fw-bold { font-weight: normal !important; }
    .row-hidden .text-primary { color: #adb5bd !important; }
</style>

<div class="page-body pb-5">
    <div class="container-fluid py-4">
        
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">Danh sách sản phẩm</h4>
                <p class="text-muted small mb-0">Quản lý kho hàng và trạng thái hiển thị</p>
            </div>
            <a href="admin.php?ctrl=admin&act=add" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Thêm mới
            </a>
        </div>

        <form method="GET" class="card card-box mb-4">
            <input type="hidden" name="ctrl" value="admin">
            <input type="hidden" name="act" value="product">
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-lg-5">
                        <label class="small fw-bold text-secondary mb-1">TÌM KIẾM</label>
                        <div class="position-relative">
                            <i class="bi bi-search position-absolute text-muted" style="top: 10px; left: 15px;"></i>
                            <input type="text" name="keyword" class="form-control rounded-pill ps-5" placeholder="Nhập tên sản phẩm..." value="<?=htmlspecialchars($_GET['keyword']??'')?>">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <label class="small fw-bold text-secondary mb-1">DANH MỤC</label>
                        <select name="cat_id" class="form-select filter-select">
                            <option value="">Tất cả</option>
                            <?php if(!empty($categories)): foreach($categories as $c): ?>
                                <option value="<?=$c['id']?>" <?=($c['id']==($_GET['cat_id']??''))?'selected':''?>><?=htmlspecialchars($c['name'])?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="small fw-bold text-secondary mb-1">KHO HÀNG</label>
                        <select name="status" class="form-select filter-select">
                            <option value="">Tất cả</option>
                            <option value="instock" <?=($_GET['status']??'')=='instock'?'selected':''?>>Còn hàng</option>
                            <option value="out" <?=($_GET['status']??'')=='out'?'selected':''?>>Hết hàng</option>
                        </select>
                    </div>
                    <div class="col-lg-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-dark w-100 rounded-pill fw-bold">Lọc ngay</button>
                    </div>
                </div>
            </div>
        </form>

        <div class="card card-box">
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Giá bán</th>
                            <th>Kho & Biến thể</th>
                            <th>Trạng thái</th>
                            <th class="text-end pe-4">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($products) || !is_array($products)): ?>
                            <tr><td colspan="6" class="text-center py-5 text-muted">Không tìm thấy dữ liệu phù hợp.</td></tr>
                        <?php else: ?>
                            <?php foreach($products as $p): 
                                // 1. Xác định trạng thái (Mặc định là 1 nếu không có cột status)
                                $isActive = isset($p['status']) ? ($p['status'] == 1) : true;
                                
                                // 2. Class CSS cho dòng (Nếu ẩn thì thêm class làm mờ)
                                $rowClass = $isActive ? '' : 'row-hidden';

                                // 3. Xử lý Badge hiển thị
                                if ($isActive) {
                                    $status_badge = $p['quantity'] > 0 ? 'badge-soft-success' : 'badge-soft-danger';
                                    $status_text  = $p['quantity'] > 0 ? 'Đang bán' : 'Hết hàng';
                                } else {
                                    $status_badge = 'badge-soft-secondary';
                                    $status_text  = 'Đã ẩn';
                                }
                                
                                // 4. Xử lý ảnh đại diện
                                $dbVal = $p['image'];
                                $imgUrl = "./Public/image/no-image.jpg";
                                if (!empty($dbVal)) {
                                    if (strpos($dbVal, 'image/') === 0) $imgUrl = "./Public/" . $dbVal;
                                    else $imgUrl = "./Public/image/" . $dbVal;
                                }

                                // 5. Xử lý giá
                                $displayPrice = isset($p['display_price']) && $p['display_price'] > 0 
                                                ? number_format($p['display_price']) . " ₫" 
                                                : "<span class='text-muted small'>--</span>";
                            ?>
                                <tr class="<?=$rowClass?>">
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <img src="<?=$imgUrl?>" class="img-thumb me-3">
                                            <div>
                                                <div class="fw-bold text-dark"><?=htmlspecialchars($p['name'])?></div>
                                                <div class="small text-muted">ID: #<?=$p['id']?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light text-dark border"><?=htmlspecialchars($p['cat_name']??'--')?></span></td>
                                    <td class="text-primary fw-bold"><?=$displayPrice?></td>
                                    
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-layers text-muted"></i>
                                            <div>
                                                <div class="fw-bold"><?=$p['quantity']?> <span class="small fw-normal text-muted">SP</span></div>
                                            </div>
                                        </div>
                                    </td>

                                    <td><span class="badge <?=$status_badge?> rounded-pill px-3"><?=$status_text?></span></td>

                                    <td class="text-end pe-4">
                                        <a href="admin.php?ctrl=admin&act=edit&id=<?=$p['id']?>" 
                                           class="btn btn-sm btn-white border shadow-sm rounded-circle me-1" 
                                           style="width:34px; height:34px;" title="Chỉnh sửa">
                                            <i class="bi bi-pencil-fill text-primary small"></i>
                                        </a>

                                        <?php if($isActive): ?>
                                            <a href="admin.php?ctrl=admin&act=toggle_status&id=<?=$p['id']?>&status=0" 
                                               class="btn btn-sm btn-white border shadow-sm rounded-circle" 
                                               style="width:34px; height:34px;" 
                                               title="Ẩn sản phẩm này (Khách sẽ không thấy)"
                                               onclick="return confirm('Bạn muốn ẨN sản phẩm này khỏi trang chủ?');">
                                               <i class="bi bi-eye-slash-fill text-danger small"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="admin.php?ctrl=admin&act=toggle_status&id=<?=$p['id']?>&status=1" 
                                               class="btn btn-sm btn-white border shadow-sm rounded-circle" 
                                               style="width:34px; height:34px;" 
                                               title="Hiển thị lại sản phẩm"
                                               onclick="return confirm('Bạn muốn HIỂN THỊ lại sản phẩm này?');">
                                               <i class="bi bi-eye-fill text-success small"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if(isset($totalPages) && $totalPages > 1): ?>
            <div class="card-footer bg-white py-3">
                <nav>
                    <ul class="pagination pagination-sm justify-content-center mb-0">
                        <?php for($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= ($page??1) == $i ? 'active' : '' ?>">
                                <a class="page-link rounded-circle mx-1 text-center" style="width:30px; height:30px; padding: 4px;" href="?<?=http_build_query(array_merge($_GET, ['page' => $i]))?>"><?=$i?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>