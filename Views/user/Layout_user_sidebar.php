<style>
    .sidebar-link {
        display: block;
        padding: 10px 15px;
        color: #333;
        text-decoration: none;
        border-radius: 5px;
        margin-bottom: 5px;
        transition: all 0.3s;
    }

    .sidebar-link:hover {
        background-color: #e9ecef;
        color: #000;
        padding-left: 20px;
    }

    .sidebar-link.active {
        background-color: #000; 
        color: #fff !important;
        font-weight: bold;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
</style>
<?php $act = $_GET['act'] ?? ''; ?>

<div class="col-lg-3 col-md-4">
    <h5 class="fw-bold mb-4">Tài khoản của tôi</h5>
    <div class="list-group border-0">
        <a href="?ctrl=user&act=updateProfile" 
           class="sidebar-link <?= ($act == 'updateProfile') ? 'active' : '' ?>">
            <i class="fas fa-user me-2"></i> Thông tin cá nhân
        </a>

        <a href="?ctrl=user&act=myOrders" 
           class="sidebar-link <?= ($act == 'myOrders' || $act == 'detail') ? 'active' : '' ?>">
            <i class="fas fa-shopping-bag me-2"></i> Đơn hàng của tôi
        </a>

        <a href="?ctrl=product&act=favorite_list" 
           class="sidebar-link <?= ($act == 'favorite_list') ? 'active' : '' ?>">
            <i class="fas fa-heart me-2"></i> Sản phẩm yêu thích
        </a>

        <a href="#" class="sidebar-link">
            <i class="fas fa-map-marker-alt me-2"></i> Địa chỉ giao hàng
        </a>
        
        <a href="?ctrl=user&act=password" class="sidebar-link <?= ($act == 'password') ? 'active' : '' ?>">
            <i class="fas fa-lock me-2"></i> Đổi mật khẩu
        </a>

        <a href="?ctrl=user&act=logout" class="sidebar-link text-danger">
            <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
        </a>
    </div>
</div>