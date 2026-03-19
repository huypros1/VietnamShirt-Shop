<?php include_once "./Views/admin/layout_sidebar_admin.php"?>
<!-- Trang Danh mục -->
        <div class="page-body">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0 text-primary"><i class="bi bi-grid-3x3-gap"></i> Quản lý danh mục</h2>
                <a href="#" class="btn btn-success btn-lg shadow">
                    <i class="bi bi-plus-lg"></i> Thêm danh mục mới
                </a>
            </div>

            <!-- Tìm kiếm nhanh -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-5">
                            <input type="text" class="form-control form-control-lg" placeholder="Tìm kiếm tên danh mục, slug...">
                        </div>
                        <div class="col-lg-3">
                            <select class="form-select form-select-lg">
                                <option>Tất cả trạng thái</option>
                                <option>Hiển thị</option>
                                <option>Ẩn</option>
                            </select>
                        </div>
                        <div class="col-lg-4 d-flex align-items-end">
                            <button class="btn btn-primary btn-lg me-2">Tìm kiếm</button>
                            <button class="btn btn-outline-secondary btn-lg">Làm mới</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bảng danh mục -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Danh sách danh mục (28)</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th width="80">#</th>
                                <th>Tên danh mục</th>
                                <th>Slug</th>
                                <th>Số sản phẩm</th>
                                <th>Trạng thái</th>
                                <th>Danh mục cha</th>
                                <th width="140" class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td><strong>Điện thoại</strong></td>
                                <td>dien-thoai</td>
                                <td><span class="badge bg-success text-white">245</span></td>
                                <td><span class="badge bg-success">Hiển thị</span></td>
                                <td>—</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning">Sửa</button>
                                    <button class="btn btn-sm btn-danger">Xóa</button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td><strong>&nbsp;&nbsp;└ Samsung</strong></td>
                                <td>samsung</td>
                                <td><span class="badge bg-info text-dark">89</span></td>
                                <td><span class="badge bg-success">Hiển thị</span></td>
                                <td>Điện thoại</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning">Sửa</button>
                                    <button class="btn btn-sm btn-danger">Xóa</button>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td><strong>&nbsp;&nbsp;└ iPhone</strong></td>
                                <td>iphone</td>
                                <td><span class="badge bg-info text-dark">156</span></td>
                                <td><span class="badge bg-success">Hiển thị</span></td>
                                <td>Điện thoại</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning">Sửa</button>
                                    <button class="btn btn-sm btn-danger">Xóa</button>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td><strong>Laptop</strong></td>
                                <td>laptop</td>
                                <td><span class="badge bg-success text-white">189</span></td>
                                <td><span class="badge bg-success">Hiển thị</span></td>
                                <td>—</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning">Sửa</button>
                                    <button class="btn btn-sm btn-danger">Xóa</button>
                                </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td><strong>Phụ kiện</strong></td>
                                <td>phu-kien</td>
                                <td><span class="badge bg-warning text-dark">412</span></td>
                                <td><span class="badge bg-secondary text-white">Ẩn</span></td>
                                <td>—</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning">Sửa</button>
                                    <button class="btn btn-sm btn-danger">Xóa</button>
                                </td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td><strong>Đồng hồ thông minh</strong></td>
                                <td>dong-ho-thong-minh</td>
                                <td><span class="badge bg-info text-dark">67</span></td>
                                <td><span class="badge bg-success">Hiển thị</span></td>
                                <td>—</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning">Sửa</button>
                                    <button class="btn btn-sm btn-danger">Xóa</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                    <small class="text-muted">Hiển thị 1-10 của 28 danh mục</small>
                    <div class="btn-group">
                        <a href="#" class="btn btn-sm btn-outline-secondary">Trước</a>
                        <a href="#" class="btn btn-sm btn-primary">1</a>
                        <a href="#" class="btn btn-sm btn-outline-secondary">2</a>
                        <a href="#" class="btn btn-sm btn-outline-secondary">3</a>
                        <a href="#" class="btn btn-sm btn-outline-secondary">Tiếp</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

</body>
</html>