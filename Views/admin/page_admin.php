<?php include_once "./Views/admin/layout_sidebar_admin.php" ?>

<!-- Page Body -->
<div class="page-body">
    <section id="dashboard-page" class="page-section">
        <h2 class="mb-4 text-primary"><i class="bi bi-speedometer2"></i> Tổng quan hệ thống</h2>
        
        <!-- Cards tổng quan -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card text-white bg-primary h-100 shadow-sm">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <h5 class="card-title mb-3"><i class="bi bi-box-seam me-2"></i>Tổng sản phẩm</h5>
                        <h2 class="mb-0 fw-bold"><?= number_format($totalProducts) ?></h2>
                        <small class="mt-2 opacity-75">Đang kinh doanh</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success h-100 shadow-sm">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <h5 class="card-title mb-3"><i class="bi bi-cart-check me-2"></i>Đơn hôm nay</h5>
                        <h2 class="mb-0 fw-bold"><?= number_format($ordersToday) ?></h2>
                        <small class="mt-2 opacity-75"><?= date('d/m/Y') ?></small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning h-100 shadow-sm">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <h5 class="card-title mb-3"><i class="bi bi-currency-exchange me-2"></i>Doanh thu tháng</h5>
                        <h2 class="mb-0 fw-bold"><?= number_format($monthlyRevenue) ?> ₫</h2>
                        <small class="mt-2 opacity-75">Tháng <?= date('m/Y') ?></small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ -->
        <div class="row g-4">
            <!-- Doanh thu 12 tháng -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-graph-up"></i> Doanh thu 12 tháng gần nhất</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="revenueChart" height="100"></canvas>
                    </div>
                </div>
            </div>

            <!-- Trạng thái đơn hàng -->
            <div class="col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Tỷ lệ đơn hàng</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="orderStatusChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Top sản phẩm bán chạy -->
            <div class="col-12 mt-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-trophy"></i> Top 10 sản phẩm bán chạy</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="topProductsChart" height="120"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Doanh thu 12 tháng
new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode(array_column($revenueLast12Months, 'month')) ?>,
        datasets: [{
            label: 'Doanh thu (₫)',
            data: <?= json_encode(array_column($revenueLast12Months, 'revenue')) ?>,
            borderColor: '#007bff',
            backgroundColor: 'rgba(0, 123, 255, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' } },
        scales: {
            y: { beginAtZero: true, ticks: { callback: value => value.toLocaleString() + '₫' } }
        }
    }
});

// Top 10 sản phẩm
new Chart(document.getElementById('topProductsChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($topProducts, 'name')) ?>,
        datasets: [{
            label: 'Số lượng đã bán',
            data: <?= json_encode(array_column($topProducts, 'total_sold')) ?>,
            backgroundColor: '#28a745'
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: { legend: { display: false } }
    }
});

// Tỷ lệ trạng thái đơn hàng
new Chart(document.getElementById('orderStatusChart'), {
    type: 'doughnut',
    data: {
        labels: <?= json_encode($statusLabels) ?>,
        datasets: [{
            data: <?= json_encode($statusData) ?>,
            backgroundColor: <?= json_encode(array_values($statusColors)) ?>
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' },
            tooltip: { callbacks: { label: ctx => ctx.label + ': ' + ctx.parsed + ' đơn' } }
        }
    }
});
</script>

</body>
</html>