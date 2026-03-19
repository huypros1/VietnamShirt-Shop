<?php include_once "./Views/admin/layout_sidebar_admin.php" ?>

<style>
    /* CSS Tinh chỉnh */
    body { background-color: #f8f9fa; }
    .card-box { border: none; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.03); background: #fff; }
    
    .upload-box { border: 2px dashed #dee2e6; border-radius: 8px; padding: 25px 10px; text-align: center; cursor: pointer; transition: all 0.2s; background: #f8f9fa; }
    .upload-box:hover { border-color: #0d6efd; background: #ecf4ff; color: #0d6efd; }
    .upload-box i { font-size: 1.8rem; color: #adb5bd; margin-bottom: 5px; display: block; }
    
    .table-custom thead th { background-color: #f8f9fa; color: #6c757d; font-weight: 600; font-size: 0.75rem; border: none; padding: 12px 16px; text-transform: uppercase; }
    .table-custom td { padding: 10px 16px; vertical-align: middle; }
    .table-custom tbody tr { border-bottom: 1px solid #f1f3f5; transition: all 0.2s; }
    
    .input-clean { border: 1px solid transparent; background: transparent; padding: 5px 8px; border-radius: 4px; font-weight: 600; color: #212529; width: 100%; }
    .input-clean:hover { background: #f8f9fa; border-color: #dee2e6; }
    .input-clean:focus { background: #fff; border-color: #86b7fe; outline: none; box-shadow: 0 0 0 2px rgba(13,110,253,.1); }
    .input-clean[readonly] { pointer-events: none; }

    /* [ĐÃ SỬA] STYLE CHO DÒNG BỊ ẨN (GIỐNG PAGE PRODUCT) */
    .row-removed { 
        background-color: #f8f9fa !important; 
        opacity: 0.5; /* Làm mờ đi */
        filter: grayscale(100%); /* Chuyển sang trắng đen */
    }
    .row-removed .input-clean { color: #adb5bd !important; } /* Chữ màu xám nhạt */
    .row-removed img { opacity: 0.6; }
    
    .img-avatar { width: 44px; height: 44px; border-radius: 8px; object-fit: cover; border: 1px solid #dee2e6; }
    .floating-save-btn { position: fixed; bottom: 30px; right: 30px; z-index: 1050; box-shadow: 0 8px 30px rgba(13, 110, 253, 0.3); }
</style>

<div class="page-body pb-5">
    <div class="container-fluid py-4">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-dark mb-0">Chỉnh sửa sản phẩm</h4>
            <a href="admin.php?ctrl=admin&act=product" class="btn btn-light border btn-sm px-3 rounded-pill fw-bold text-secondary">
                <i class="bi bi-arrow-left me-1"></i> Quay lại
            </a>
        </div>

        <form method="POST" enctype="multipart/form-data">
            
            <datalist id="suggested_colors"><option value="Trắng"><option value="Đen"><option value="Xanh Navy"><option value="Hồng Pastel"><option value="Đỏ Đô"><option value="Vàng Chanh"><option value="Xám Khói"><option value="Be"></datalist>
            <datalist id="suggested_sizes"><option value="S"><option value="M"><option value="L"><option value="XL"><option value="XXL"><option value="3XL"><option value="FreeSize"></datalist>

            <div class="card card-box mb-4">
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-lg-3 text-center border-end">
                            <label class="form-label fw-bold text-secondary small d-block mb-3">ẢNH ĐẠI DIỆN</label>
                            <div class="position-relative d-inline-block">
                                <?php 
                                    $mainImg = "./Public/image/no-image.jpg";
                                    if(!empty($product['image'])) {
                                        if(file_exists("./Public/" . $product['image'])) $mainImg = "./Public/" . $product['image'];
                                        elseif(file_exists("./Public/image/" . $product['image'])) $mainImg = "./Public/image/" . $product['image'];
                                    }
                                ?>
                                <img src="<?=$mainImg?>" class="rounded-3 shadow-sm border mb-2" style="width: 140px; height: 140px; object-fit: contain; background:#f9fafb;">
                                <input type="file" name="image" class="form-control form-control-sm">
                                <input type="hidden" name="current_image" value="<?=$product['image']?>">
                            </div>
                        </div>
                        
                        <div class="col-lg-9 ps-lg-4">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label fw-bold text-secondary small">TÊN SẢN PHẨM <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control fw-bold" value="<?=htmlspecialchars($product['name'])?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-secondary small">DANH MỤC <span class="text-danger">*</span></label>
                                    <select name="category_id" class="form-select fw-bold text-primary" required>
                                        <?php foreach($categories as $c): ?>
                                            <option value="<?=$c['id']?>" <?=($c['id']==$product['category_id'])?'selected':''?>><?=$c['name']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold text-secondary small">MÔ TẢ</label>
                                    <textarea name="description" class="form-control" rows="3"><?=htmlspecialchars($product['description']??'')?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                
                <div class="col-lg-4">
                    <div class="card card-box h-100">
                        <div class="card-body p-4">
                            <h6 class="fw-bold text-dark mb-3 border-bottom pb-2">Thêm biến thể mới</h6>
                            
                            <div class="mb-3">
                                <label class="upload-box w-100 p-3">
                                    <i class="bi bi-cloud-arrow-up-fill text-primary"></i>
                                    <span class="d-block fw-bold small text-dark">Chọn ảnh</span>
                                    <input type="file" name="new_variant_image" class="d-none" onchange="this.previousElementSibling.innerText = this.files[0].name; this.previousElementSibling.classList.add('text-primary');">
                                </label>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold text-secondary">Màu</label>
                                    <input type="text" name="new_variant[color]" list="suggested_colors" class="form-control fw-bold" placeholder="Nhập màu">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold text-secondary">Size</label>
                                    <input type="text" name="new_variant[size]" list="suggested_sizes" class="form-control fw-bold" placeholder="Nhập size">
                                </div>
                            </div>

                            <div class="row g-2 mb-4">
                                <div class="col-6">
                                    <label class="form-label small fw-bold text-secondary">Giá</label>
                                    <input type="number" name="new_variant[price]" class="form-control" placeholder="0" min="0">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold text-secondary">Kho</label>
                                    <input type="number" name="new_variant[stock]" class="form-control" placeholder="0" min="0">
                                </div>
                            </div>

                            <button type="submit" name="btn_add" value="1" class="btn btn-outline-primary w-100 py-2 rounded-pill fw-bold border-2">
                                <i class="bi bi-plus-lg"></i> Thêm vào danh sách
                            </button>
                            <div class="form-text text-center small mt-2 text-muted">Bấm "Thêm" để đưa sang bảng bên phải</div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card card-box h-100">
                        <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold text-dark m-0">Danh sách biến thể (<?=count($variants)?>)</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-custom mb-0">
                                    <thead>
                                        <tr>
                                            <th class="ps-4">Ảnh</th>
                                            <th>Màu / Size</th>
                                            <th>Giá bán</th>
                                            <th>Kho</th>
                                            <th class="text-end pe-4">Ẩn/Hiện</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($variants)): ?>
                                            <tr><td colspan="5" class="text-center py-5 text-muted">Chưa có biến thể.</td></tr>
                                        <?php else: foreach($variants as $k => $v): 
                                            $vColor = $v['color'] ?? '';
                                            $vSize  = $v['size'] ?? '';
                                            $vPrice = $v['price'] ?? 0;
                                            $vStock = $v['stock'] ?? 0;
                                            $vImage = $v['image'] ?? '';
                                            
                                            // [SỬA] Kiểm tra trạng thái dựa trên cột status
                                            // Nếu status là 'hide' thì coi như bị ẩn
                                            $isHidden = (isset($v['status']) && $v['status'] === 'hide');
                                            
                                            $imgSrc = "";
                                            if(!empty($vImage)) {
                                                if(file_exists("./Public/" . $vImage)) $imgSrc = "./Public/" . $vImage;
                                                elseif(file_exists("./Public/image/" . $vImage)) $imgSrc = "./Public/image/" . $vImage;
                                                elseif(file_exists($vImage)) $imgSrc = $vImage;
                                            }
                                        ?>
                                            <tr class="<?=$isHidden ? 'row-removed' : ''?>">
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center">
                                                        <?php if($imgSrc): ?>
                                                            <img src="<?=$imgSrc?>" class="img-avatar me-2">
                                                        <?php else: ?>
                                                            <div class="img-avatar bg-light d-flex align-items-center justify-content-center me-2 text-muted small"><i class="bi bi-image"></i></div>
                                                        <?php endif; ?>
                                                        
                                                        <?php if(!$isHidden): ?>
                                                            <div style="width: 30px; overflow:hidden;">
                                                                <label class="btn btn-sm btn-white border p-0 rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width:24px; height:24px; cursor:pointer;" title="Đổi ảnh">
                                                                    <i class="bi bi-pencil-fill" style="font-size: 8px;"></i>
                                                                    <input type="file" name="variants_files[<?=$k?>]" class="d-none">
                                                                </label>
                                                            </div>
                                                        <?php endif; ?>
                                                        
                                                        <input type="hidden" name="variants[<?=$k?>][id]" value="<?=$v['id'] ?? ''?>">
                                                        <input type="hidden" name="variants[<?=$k?>][image]" value="<?=$vImage?>">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-1 align-items-center">
                                                        <input type="text" name="variants[<?=$k?>][color]" list="suggested_colors" class="input-clean fw-bold text-dark" style="width: 70px;" value="<?=$vColor?>" <?=$isHidden?'readonly':''?>>
                                                        <span class="text-muted small">/</span>
                                                        <input type="text" name="variants[<?=$k?>][size]" list="suggested_sizes" class="input-clean fw-bold text-dark text-center" style="width: 50px;" value="<?=$vSize?>" <?=$isHidden?'readonly':''?>>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="number" name="variants[<?=$k?>][price]" class="input-clean text-primary fw-bold" value="<?=$vPrice?>" min="0" <?=$isHidden?'readonly':''?>>
                                                </td>
                                                <td>
                                                    <input type="number" name="variants[<?=$k?>][stock]" class="input-clean text-center fw-bold" style="width: 60px;" value="<?=$vStock?>" min="0" <?=$isHidden?'readonly':''?>>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <input type="hidden" name="variants[<?=$k?>][status]" value="<?=$isHidden ? 'hide' : 'show'?>">
                                                    
                                                    <?php if($isHidden): ?>
                                                        <button type="submit" name="btn_unhide" value="<?=$k?>" class="btn btn-sm btn-light text-success border rounded-circle shadow-sm" style="width:32px; height:32px;" title="Hiện lại">
                                                            <i class="bi bi-eye-fill"></i>
                                                        </button>
                                                    <?php else: ?>
                                                        <button type="submit" name="btn_hide" value="<?=$k?>" class="btn btn-sm btn-light text-danger border rounded-circle shadow-sm" style="width:32px; height:32px;" title="Ẩn đi">
                                                            <i class="bi bi-eye-slash-fill"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" name="btn_save" value="1" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold floating-save-btn">
                <i class="bi bi-check2-circle me-2"></i> LƯU CẬP NHẬT
            </button>

        </form>
    </div>
</div>