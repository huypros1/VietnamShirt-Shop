<section class="container my-5">
  <form id="filter-form" action="" method="get">
    <input type="hidden" name="ctrl" value="product">
    <input type="hidden" name="act" value="list">
    <?php if (isset($_GET['keyword'])): ?>
        <input type="hidden" name="keyword" value="<?= htmlspecialchars($_GET['keyword']) ?>">
    <?php endif; ?>

    <div class="row">
      <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm p-4">
          <h5 class="mb-4 fw-bold"><i class="fas fa-filter me-2"></i>Lọc sản phẩm</h5>
          
          <div class="mb-4">
              <h6 class="fw-bold mb-3">Danh mục</h6>

              <?php 
                $cats = [
              1 => "Áo thun",
              2 => "Áo sơ mi",
              3 => "Áo khoác",
              4 => "Áo len",
              5 => "Áo polo",
            ];
              ?>

              <?php foreach($cats as $key => $text): ?>
                <div class="form-check mb-2">
                 <input 
                type="checkbox" 
                class="form-check-input"
                name="category[]"
                value="<?= $key ?>"
                id="<?= $key ?>"
                <?= (isset($_GET['category']) && in_array($key, $_GET['category'])) ? 'checked' : '' ?>
            >

                  <label class="form-check-label" for="<?= $key ?>"><?= $text ?></label>
                </div>
              <?php endforeach; ?>
            </div>

          
          <div class="mb-4">
                <h6 class="fw-bold mb-3">Khoảng giá</h6>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="priceRange" value="duoi-200"
                    <?= (isset($_GET['priceRange']) && $_GET['priceRange']=='duoi-200') ? 'checked' : '' ?>>
                    <label class="form-check-label">Dưới 200.000₫</label>
                </div>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="priceRange" value="200-400"
                    <?= (isset($_GET['priceRange']) && $_GET['priceRange']=='200-400') ? 'checked' : '' ?>>
                    <label class="form-check-label">200.000₫ - 400.000₫</label>
                </div>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="priceRange" value="400-600"
                    <?= (isset($_GET['priceRange']) && $_GET['priceRange']=='400-600') ? 'checked' : '' ?>>
                    <label class="form-check-label">400.000₫ - 600.000₫</label>
                </div>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="priceRange" value="tren-600"
                    <?= (isset($_GET['priceRange']) && $_GET['priceRange']=='tren-600') ? 'checked' : '' ?>>
                    <label class="form-check-label">Trên 600.000₫</label>
                </div>
            </div>
          
          <button type="submit" class="btn btn-danger w-100 py-2 fw-bold">Áp dụng lọc</button>
        </div>
      </div>
      
      <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h4 class="mb-0 fw-bold">
            <?php 
              if (isset($_GET['keyword']) && !empty(trim($_GET['keyword']))) {
                  echo 'Kết quả cho "' . htmlspecialchars($_GET['keyword']) . '"';
              } else {
                  echo 'Tất cả sản phẩm';
              }
            ?>
            (<?= count($productList) ?>)
          </h4>
          <div>
            <span class="me-2">Sắp xếp:</span>
            <select name="sort" class="form-select form-select-sm w-auto d-inline-block" onchange="document.getElementById('filter-form').submit();">
              <option value="newest" <?= (isset($_GET['sort']) && $_GET['sort'] == 'newest') ? 'selected' : '' ?>>Sản phẩm mới nhất</option>
              <option value="price-asc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price-asc') ? 'selected' : '' ?>>Giá: Thấp → Cao</option>
              <option value="price-desc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price-desc') ? 'selected' : '' ?>>Giá: Cao → Thấp</option>
              <option value="name-asc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'name-asc') ? 'selected' : '' ?>>Tên A → Z</option>
            </select>
          </div>
        </div>

        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
          <?php foreach ($productList as $product): ?>
             <?php 
                // XỬ LÝ ẢNH
                $hinh = "Public/image/" . $product['image'];
                if (strpos($product['image'], 'image/') === 0) {
                    $hinh = "Public/" . $product['image'];
                }
            ?>
            <div class="col">
              <div class="card h-100 border-0 shadow-sm hover:shadow-lg transition-all duration-300">
                <div class="position-relative overflow-hidden">
                  <img src="<?= htmlspecialchars($hinh) ?>" 
                       class="card-img-top" 
                       alt="<?= htmlspecialchars($product["name"]) ?>"
                       style="height: 250px; object-fit: cover;">
                  <?php if(!empty($product["sale"])): ?>
                    <span class="position-absolute top-0 start-0 bg-danger text-white px-2 py-1 small fw-bold">
                      -<?= $product["sale"] ?>%
                    </span>
                  <?php endif; ?>
                </div>
                
                <div class="card-body d-flex flex-column p-3">
                  <h6 class="card-title text-truncate mb-2" title="<?= htmlspecialchars($product["name"]) ?>">
                    <?= htmlspecialchars($product["name"]) ?>
                  </h6>
                  
                  <div class="mt-auto">
                    <p class="text-danger fw-bold fs-5 mb-1">
                      <?= number_format($product["price"], 0, ',', '.') ?>₫
                    </p>
                    <?php if(!empty($product["original_price"]) && $product["original_price"] > $product["price"]): ?>
                      <del class="text-muted small">
                        <?= number_format($product["original_price"], 0, ',', '.') ?>₫
                      </del>
                    <?php endif; ?>
                  </div>
                </div>
                
                <div class="card-footer bg-white border-0 p-3 text-center d-flex gap-2">
                  <a href="?ctrl=product&act=detail&id=<?= $product["id"] ?>" 
                    class="btn btn-outline-secondary btn-sm flex-grow-1 fw-bold">
                    Xem chi tiết
                  </a>
                  <a href="?ctrl=cart&act=add&id=<?=$product['id']?>" 
                    class="btn btn-danger btn-sm fw-bold">
                    <i class="fas fa-cart-plus"></i>
                  </a>
              </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <?php if(empty($productList)): ?>
          <div class="text-center py-5">
            <p class="text-muted fs-4">Không tìm thấy sản phẩm nào phù hợp.</p>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </form>
</section>