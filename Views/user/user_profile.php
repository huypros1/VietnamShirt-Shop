<!-- Views/user/user_profile.php -->
<?php
// XÓA ĐOẠN NÀY ĐI (không cần nữa vì Controller đã truyền $user rồi)
// if (!isset($user)) {
//     $userModel = new User();
//     $user = $userModel->profile($_SESSION['user']['id'] ?? 0);
// }

// Giữ nguyên phần còn lại của file – chỉ cần để nguyên như bạn đang có
?>

<main class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <?php include_once "./Views/user/Layout_user_sidebar.php"; ?>

            <div class="col-lg-9 col-md-8">
                <div class="bg-white p-4 p-md-5 shadow-sm rounded">
                    <h4 class="mb-4 text-danger fw-bold">Thông tin cá nhân</h4>

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

                    <form action="?ctrl=user&act=postUpdateProfile" method="POST" class="row g-4">
                        <!-- Toàn bộ form giữ nguyên như bạn đang có -->
                        <div class="col-md-6">
                            <label>Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label>Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label>Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label>Ngày sinh</label>
                            <input type="date" name="birthday" class="form-control" value="<?= $user['birthday'] ?? '' ?>">
                        </div>
                        <div class="col-12">
                            <label>Địa chỉ nhận hàng mặc định</label>
                            <textarea name="address" rows="3" class="form-control"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                        </div>
                        <div class="col-12">
                            <label>Giới tính</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" value="nam" <?= (!isset($user['gender']) || $user['gender'] == 'nam') ? 'checked' : '' ?>>
                                <label class="form-check-label">Nam</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" value="nu" <?= ($user['gender'] ?? '') == 'nu' ? 'checked' : '' ?>>
                                <label class="form-check-label">Nữ</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" value="khac" <?= ($user['gender'] ?? '') == 'khac' ? 'checked' : '' ?>>
                                <label class="form-check-label">Khác</label>
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-danger btn-lg px-5 rounded-pill">
                                Cập nhật thông tin
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