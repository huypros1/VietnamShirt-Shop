<!-- Views/user/user_password.php -->

<main class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <?php include_once "./Views/user/Layout_user_sidebar.php"; ?>

            <div class="col-lg-9 col-md-8">
                <div class="bg-white p-4 p-md-5 shadow-sm rounded">
                    <h4 class="mb-4 text-danger fw-bold">Đổi mật khẩu</h4>

                    <?php if (isset($_SESSION['info'])): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= $_SESSION['info']; unset($_SESSION['info']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="?ctrl=user&act=postPassword" method="POST" class="row g-4" id="passwordForm">
                        <div class="col-md-6">
                            <label>Mật khẩu hiện tại <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" name="current_password" class="form-control" id="current_password" required>
                                <span class="input-group-text toggle-password" data-target="current_password">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6"></div> <!-- Cân bằng layout -->

                        <div class="col-md-6">
                            <label>Mật khẩu mới <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" name="new_password" class="form-control" id="new_password" required>
                                <span class="input-group-text toggle-password" data-target="new_password">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label>Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" name="confirm_password" class="form-control" id="confirm_password" required>
                                <span class="input-group-text toggle-password" data-target="confirm_password">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>

                        <div class="col-12">
                            <small class="text-muted">
                                Mật khẩu mới nên có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt để tăng độ bảo mật.
                            </small>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-danger btn-lg px-5 rounded-pill">
                                Cập nhật mật khẩu
                            </button>
                            <a href="?ctrl=page&act=home" class="btn btn-secondary btn-lg rounded-pill ms-3">
                                Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- JavaScript để toggle hiển thị mật khẩu -->
<script>
document.querySelectorAll('.toggle-password').forEach(item => {
    item.addEventListener('click', function () {
        const targetId = this.getAttribute('data-target');
        const input = document.getElementById(targetId);
        
        if (input.type === 'password') {
            input.type = 'text';
            this.innerHTML = '<i class="fas fa-eye-slash"></i>';
        } else {
            input.type = 'password';
            this.innerHTML = '<i class="fas fa-eye"></i>';
        }
    });
});
</script>