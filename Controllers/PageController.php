<?php
// Dùng để quản lý các trang không liên quan
// Vd: Trang chủ, trang liên hệ, trang giới thiệu
class PageController
{
  function home()
  {
    include_once "./Models/Product.php";
    $productModel = new Product();
    // Lấy danh sách sản phẩm bán chạy từ SQL
    $bestSellingProducts = $productModel->BestSelling(4);
    $newProducts = $productModel->NewProducts(4);
    $recommendedProducts = $productModel->RecommendedProducts(4);
    // Hiển thị dữ liệu
    include_once "./Views/user/page_home.php";
  }
  
}
?>