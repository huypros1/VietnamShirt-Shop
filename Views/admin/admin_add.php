<?php include_once "./Views/admin/layout_sidebar_admin.php" ?>

<style>
    /* ... (Giữ nguyên CSS cũ) ... */
    body { background-color: #f8f9fa; }
    .card-box { border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); background: #fff; }
    .form-control, .form-select { border-radius: 8px; padding: 10px 15px; border: 1px solid #dee2e6; }
    .form-control:focus, .form-select:focus { border-color: #86b7fe; box-shadow: 0 0 0 0.25rem rgba(13,110,253,.15); }
    .upload-placeholder {
        border: 2px dashed #cbd5e1; border-radius: 12px; height: 200px; display: flex; flex-direction: column; 
        justify-content: center; align-items: center; background: #f8fafc; cursor: pointer; transition: all 0.2s;
    }
    .upload-placeholder:hover { border-color: #3b82f6; background: #eff6ff; color: #3b82f6; }
</style>

<div class="page-body pb-5">
    <div class="container-fluid py-4">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-dark mb-0">Thêm sản phẩm mới</h4>
            <a href="admin.php?ctrl=admin&act=product" class="btn btn-light border btn-sm px-3 rounded-pill fw-bold text-secondary">
                <i class="bi bi-arrow-left me-1"></i> Quay lại
            </a>
        </div>

        <form method="POST" enctype="multipart/form-data">
            
            <datalist id="suggested_colors">
                <option value="Trắng"><option value="Đen"><option value="Xanh Navy">
                <option value="Hồng Pastel"><option value="Đỏ Đô"><option value="Vàng Chanh">
                <option value="Xám Khói"><option value="Be">
            </datalist>

            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card card-box h-100">
                        <div class="card-body p-4 text-center">
                            <h6 class="fw-bold text-secondary mb-3 text-start">1. ẢNH ĐẠI DIỆN</h6>
                            <label class="upload-placeholder w-100 mb-3">
                                <i class="bi bi-cloud-arrow-up-fill fs-1 text-muted mb-2"></i>
                                <span class="fw-bold text-dark small">Bấm để chọn ảnh</span>
                                <span class="text-muted small" style="font-size: 0.75rem;">(JPG, PNG, WEBP)</span>
                                <input type="file" name="image" class="d-none" onchange="this.previousElementSibling.previousElementSibling.innerText = this.files[0].name; this.parentElement.classList.add('border-primary');">
                            </label>
                            <div class="alert alert-light border small text-muted text-start mb-0">
                                <i class="bi bi-info-circle me-1"></i> Ảnh này sẽ là ảnh bìa chính của sản phẩm.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card card-box h-100">
                        <div class="card-body p-4">
                            <h6 class="fw-bold text-secondary mb-4">2. THÔNG TIN CƠ BẢN</h6>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark small">Tên sản phẩm <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control form-control-lg fw-bold" placeholder="VD: Áo Thun Polo Premium..." required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold text-dark small">Danh mục <span class="text-danger">*</span></label>
                                    <select name="category_id" class="form-select form-select-lg text-primary fw-bold" required>
                                        <option value="" disabled selected>-- Chọn danh mục --</option>
                                        <?php foreach($categories as $c): ?>
                                            <option value="<?=$c['id']?>"><?=$c['name']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold text-dark small">Màu mặc định <span class="text-danger">*</span></label>
                                    <input type="text" name="default_color" list="suggested_colors" class="form-control form-control-lg fw-bold" placeholder="VD: Trắng" required>
                                    <div class="form-text small">Sẽ tạo tự động biến thể đầu tiên với màu này.</div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark small">Mô tả sản phẩm</label>
                                <textarea name="description" class="form-control" rows="5" placeholder="Nhập mô tả chi tiết..."></textarea>
                            </div>

                            <div class="d-flex justify-content-end pt-2">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow-sm">
                                    Tiếp tục: Cập nhật giá & Size <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>