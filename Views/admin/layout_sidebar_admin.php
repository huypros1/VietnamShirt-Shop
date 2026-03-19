<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản trị hệ thống</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root { 
            --sidebar-w: 260px; 
            --primary-color: #0d6efd;
            --bg-body: #f5f7fa;
        }
        * { box-sizing: border-box; margin:0; padding:0; }
        
        body {
            font-family: 'Inter', sans-serif; /* Font hiện đại hơn */
            background: var(--bg-body);
            min-height: 100vh;
            display: flex;
            color: #344767;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: var(--sidebar-w);
            background: #1e293b;
            color: #cbd5e1;
            position: fixed;
            top: 0; bottom: 0; left: 0;
            padding: 2rem 0;
            z-index: 1000;
            transition: all 0.3s;
        }
        .sidebar h4 {
            color: white;
            text-align: center;
            margin-bottom: 2.5rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .sidebar a {
            display: flex;
            align-items: center;
            padding: 0.9rem 2rem;
            color: #a0aec0;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }
        .sidebar a:hover {
            color: white;
            background: rgba(255,255,255,0.05);
        }
        .sidebar a.active {
            color: white;
            background: linear-gradient(90deg, rgba(13,110,253,0.2) 0%, rgba(13,110,253,0) 100%);
            border-left-color: var(--primary-color);
        }
        .sidebar a i { margin-right: 12px; font-size: 1.1rem; }

        /* --- MAIN CONTENT --- */
        .main-content {
            margin-left: var(--sidebar-w);
            width: calc(100% - var(--sidebar-w));
            display: flex;
            flex-direction: column;
        }
        .page-body { padding: 2rem; flex: 1; }

        /* --- COMMON COMPONENTS (DESIGN SYSTEM) --- */
        
        /* 1. Card Box (Khung trắng bo tròn, đổ bóng) */
        .card-box {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02), 0 2px 4px -1px rgba(0,0,0,0.02);
            background: #fff;
            transition: transform 0.2s;
        }
        .card-header-modern {
            background: white;
            border-bottom: 1px solid #f1f3f5;
            padding: 1.25rem 1.5rem;
            border-radius: 12px 12px 0 0;
            font-weight: 700;
            color: #344767;
        }

        /* 2. Table Modern (Bảng hiện đại) */
        .table-modern { margin-bottom: 0; }
        .table-modern thead th { 
            background-color: #f9fafb; 
            color: #6c757d; 
            font-weight: 600; 
            font-size: 0.75rem; 
            text-transform: uppercase; 
            letter-spacing: 0.5px;
            border-bottom: 1px solid #edf2f7;
            padding: 1rem 1.5rem; 
        }
        .table-modern tbody tr { border-bottom: 1px solid #f1f3f5; transition: background 0.2s; }
        .table-modern tbody tr:hover { background-color: #f8f9fa; }
        .table-modern td { padding: 1rem 1.5rem; vertical-align: middle; font-size: 0.9rem; }
        
        /* 3. Badges (Soft Color - Màu nhạt chữ đậm) */
        .badge-soft-success { background-color: #d1e7dd; color: #0f5132; }
        .badge-soft-warning { background-color: #fff3cd; color: #664d03; }
        .badge-soft-danger { background-color: #f8d7da; color: #842029; }
        .badge-soft-info { background-color: #cff4fc; color: #055160; }
        .badge-soft-secondary { background-color: #e2e3e5; color: #41464b; }
        .badge { padding: 0.5em 0.8em; font-weight: 600; border-radius: 6px; }

        /* 4. Form Controls */
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 0.6rem 1rem;
        }
        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 3px rgba(13,110,253,0.15);
            border-color: #86b7fe;
        }
        .btn { border-radius: 8px; font-weight: 500; padding: 0.5rem 1.2rem; }

        /* 5. Helpers */
        .text-small { font-size: 0.85rem; }
        .fw-bold { font-weight: 600 !important; }
        .shadow-soft { box-shadow: 0 10px 15px -3px rgba(0,0,0,0.03); }
        .avatar-sm { width: 40px; height: 40px; border-radius: 8px; object-fit: cover; }
    </style>
</head>
<body>

    <nav class="sidebar">
        <h4><i class="bi bi-shield-lock-fill me-2"></i>Admin Panel</h4>
        <div class="px-2">
            <?php $act = $_GET['act'] ?? 'page'; ?>
            
            <a href="admin.php?ctrl=admin&act=page" class="<?= $act=='page'?'active':'' ?>">
                <i class="bi bi-speedometer2"></i> Tổng quan
            </a>
            <a href="admin.php?ctrl=admin&act=product" class="<?= in_array($act, ['product', 'add', 'edit'])?'active':'' ?>">
                <i class="bi bi-box-seam"></i> Sản phẩm
            </a>
            <a href="admin.php?ctrl=admin&act=categories" class="<?= in_array($act, ['categories', 'category_add', 'category_edit'])?'active':'' ?>">
                <i class="bi bi-grid-3x3-gap"></i> Danh mục
            </a>
            <a href="admin.php?ctrl=admin&act=order" class="<?= in_array($act, ['order', 'order_detail'])?'active':'' ?>">
                <i class="bi bi-cart-check"></i> Đơn hàng
            </a>
            <a href="admin.php?ctrl=admin&act=user" class="<?= $act=='users'?'active':'' ?>">
                <i class="bi bi-person-circle"></i> Tài khoản
            </a>
            <div class="border-top border-secondary my-3 mx-3 opacity-25"></div>
            <a href="index.php?ctrl=page&act=home">
                <i class="bi bi-box-arrow-right"></i> Về trang chủ
            </a>
        </div>
    </nav>

    <div class="main-content">