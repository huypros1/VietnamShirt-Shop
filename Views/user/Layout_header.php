<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Vietnam Shirt</title>
    <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- FontAwesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <!-- Tệp CSS riêng -->
  <link rel="stylesheet" href="Public/css/style.css" />
  <style>
    /* Product row helpers */
    .product-row {
        display: flex;
        justify-content: space-between;
        flex-wrap: nowrap;
        overflow-x: auto;
    }
    .product-col {
        flex: 1 0 0;
        min-width: 200px; /* Đảm bảo chiều rộng tối thiểu để tránh co lại quá nhỏ */
        margin-right: 15px; /* Khoảng cách giữa các sản phẩm */
    }
    .product-col:last-child {
        margin-right: 0; /* Bỏ margin cho sản phẩm cuối cùng */
    }

    /* Dropdown styles (CSS-only): hover, keyboard focus and checkbox toggle for mobile */
    .dropdown-container { position: relative; display: inline-block; }
    .dropdown-toggle-checkbox { position: absolute; left: -9999px; }
    .dropdown-toggle-label { cursor: pointer; display: inline-flex; align-items: center; gap: .4rem; padding: .25rem .5rem; border-radius: .375rem; }
    .dropdown-toggle-label:focus { outline: 2px solid rgba(13,110,253,.18); outline-offset: 2px; }

    .dropdown-menu {
        display: none;
        position: absolute;
        top: calc(100% + .35rem);
        right: 0;
        left: auto;
        margin: 0;
        min-width: 12rem;
        z-index: 1000;
        background: #fff;
        border: 1px solid rgba(0,0,0,.08);
        border-radius: .375rem;
        padding: .25rem 0;
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.08);
        transform-origin: top right;
        transition: opacity .15s ease, transform .15s ease;
        opacity: 0;
        transform: translateY(-6px);
    }

    /* Show menu on hover, focus-within or when checkbox checked (tap) */
    .dropdown-container:hover .dropdown-menu,
    .dropdown-container:focus-within .dropdown-menu,
    .dropdown-toggle-checkbox:checked ~ .dropdown-menu {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }

    /* Rotate caret when open */
    .dropdown-toggle-label .fa-caret-down { transition: transform .15s ease; }
    .dropdown-toggle-checkbox:checked + .dropdown-toggle-label .fa-caret-down,
    .dropdown-container:focus-within .dropdown-toggle-label .fa-caret-down,
    .dropdown-container:hover .dropdown-toggle-label .fa-caret-down { transform: rotate(180deg); }

    .dropdown-menu .dropdown-item { white-space: nowrap; padding: .5rem 1rem; color: #212529; text-decoration: none; display: block; }
    .dropdown-menu .dropdown-item:hover { background-color: #f8f9fa; }

    @media (max-width: 575.98px) {
        .dropdown-menu { left: 0; right: 0; min-width: auto; width: 200px; }
        .dropdown-container { display: block; }
        .dropdown-toggle-label { width: 100%; }
    }
    .sidebar-link {
            display: block;
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: all 0.3s;
        }
        .sidebar-link:hover,
        .sidebar-link.active {
            background-color: #dc3545;
            color: white;
        }
        .sidebar-link i {
            width: 24px;
        }
        @media (max-width: 768px) {
            .sidebar {
                margin-bottom: 30px;
            }
        }
  </style>
</head>

<body>
    <!-- Header -->
    <header class="bg-light border-bottom sticky-top">
        <div class="container d-flex align-items-center justify-content-between py-2">
            <a href="?ctrl=page&act=home" class="text-decoration-none">
                <img src="Public/image/Vietnam shirt.jpg" alt="Vietnam Shirt Logo" class="header-logo" width="100px">
            </a>
            <form class="d-flex flex-grow-1 mx-3 position-relative" role="search" method="get" action="">
                <input type="hidden" name="ctrl" value="product">
                <input type="hidden" name="act" value="list">

                <input type="hidden" name="submit_search" value="1">

                <input class="form-control me-2 rounded-pill" 
                    type="search" 
                    name="keyword" 
                    placeholder="Bạn đang tìm gì..." 
                    aria-label="Search" 
                    value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">

                <button class="btn btn-danger rounded-pill" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            <div class="d-flex align-items-center gap-4">

                <?php if (isset($_SESSION['user'])): ?>
                    <li class="nav-item text-dark text-decoration-none small d-flex align-items-center gap-1">
                        <div class="dropdown-container">
                            <input id="user-dropdown" class="dropdown-toggle-checkbox" type="checkbox" />
                            <label for="user-dropdown" class="dropdown-toggle-label nav-link text-dark text-decoration-none small d-flex align-items-center gap-1">
                                <i class="fa fa-user"></i>
                                <span>Chào, <?= htmlspecialchars($_SESSION['user']['name'], ENT_QUOTES, 'UTF-8') ?></span>
                                <i class="fa fa-caret-down ms-1" aria-hidden="true"></i>
                            </label>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="?ctrl=user&act=updateProfile">Thông tin cá nhân</a></li>
                                <li><a class="dropdown-item" href="?ctrl=user&act=logout">Đăng xuất</a></li>
                            </ul>
                        </div>
                    </li>
                <?php else: ?>
                        <a class="nav-link text-dark text-decoration-none small d-flex align-items-center gap-1" href="?ctrl=user&act=login"><i class="fa fa-user"></i>Đăng nhập</a>
                <?php endif; ?>

                <a href="?ctrl=cart&act=index" class="text-dark text-decoration-none small d-flex align-items-center gap-1"><i class="fa fa-cart-shopping"></i>Giỏ hàng</a>
            </div>
        </div>
        <!-- Menu -->
        <nav>
            <div class="container d-flex flex-wrap justify-content-center gap-3 small fw-semibold">
                <a href="?ctrl=page&act=home" class="nav-link px-2 small text-uppercase">Trang chủ</a>
                <a href="gioithieu.html" class="nav-link px-2 small text-uppercase">Giới thiệu</a>
                <a href="?ctrl=product&act=list" class="nav-link px-2 small text-uppercase">Sản phẩm</a>
                <a href="tintuc.html" class="nav-link px-2 small text-uppercase">Tin tức</a>
                <a href="lienhe.html" class="nav-link px-2 small text-uppercase">Liên hệ</a>
            </div>
        </nav>
    </header>