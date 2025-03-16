<?php

include('../middleware/adminMiddleware.php');
include('includes/header.php');

$current_year = date('Y');
$current_month = date('m');
$selected_month = isset($_POST['month']) ? $_POST['month'] : null;
// Get the monthly sales data
$monthly_sales = getMonthlySales($current_year, $selected_month);

// Prepare data for Chart.js
$labels = [];
$data = [];

// Using DateTime to format month names
foreach ($monthly_sales as $sales) {
    $date = DateTime::createFromFormat('Y-m', $sales['month']);
    $labels[] = $date->format('M'); // 'M' for short month name
    $data[] = $sales['total_sales'];
}

$sales_data = [];
$daily_sales = [];
if (!empty($selected_month) && $selected_month != null) {
    $daily_sales = getDailySales($current_year, intval($selected_month));
}else{
    $daily_sales = getDailySales($current_year, intval($current_month));
}

// Prepare daily sales data for Chart.js
$daily_labels = [];
$daily_data = [];

// Ensure all days of the month are included
if (!empty($selected_month) && $selected_month != null) {
    $days_in_month = cal_days_in_month(CAL_GREGORIAN, intval($selected_month), $current_year);
    for ($day = 1; $day <= $days_in_month; $day++) {
        $daily_labels[] = $day;
        $daily_data[] = isset($daily_sales[$day]) ? $daily_sales[$day] : 0;
    }
}else{
    $days_in_month = cal_days_in_month(CAL_GREGORIAN, intval($current_month), $current_year);
    for ($day = 1; $day <= $days_in_month; $day++) {
        $daily_labels[] = $day;
        $daily_data[] = isset($daily_sales[$day]) ? $daily_sales[$day] : 0;
    }
}

$pendingBookings=countAllPending();
$allBookings = countAllOrders();
$allUsers = countAllUsers();
$countAllProduct = countAllProduct();

// Get current date
$current_date = date('Y-m-d');

// Calculate the start and end of the current week (Monday to Sunday)
$current_week_start = date('Y-m-d', strtotime('monday this week', strtotime($current_date)));
$current_week_end = date('Y-m-d', strtotime('sunday this week', strtotime($current_date)));

// Calculate the start and end of the previous week (Monday to Sunday)
$previous_week_start = date('Y-m-d', strtotime('monday last week', strtotime($current_date)));
$previous_week_end = date('Y-m-d', strtotime('sunday last week', strtotime($current_date)));

// Get the order counts for current and previous weeks
$current_week_orders = countOrdersForWeek($current_week_start, $current_week_end);
$previous_week_orders = countOrdersForWeek($previous_week_start, $previous_week_end);

$current_week_users = countUsersForWeek($current_week_start, $current_week_end);
$previous_week_users = countUsersForWeek($previous_week_start, $previous_week_end);

// Calculate the percentage change
if ($previous_week_orders > 0) {
    $percentage_change = (($current_week_orders - $previous_week_orders) / $previous_week_orders) * 100;
} else {
    $percentage_change = $current_week_orders > 0 ? 100 : 0; // Handle division by zero and edge cases
}

//percentage users
if ($previous_week_users > 0) {
    $percentage_change_users = (($current_week_users - $previous_week_users) / $previous_week_users) * 100;
} else {
    $percentage_change_users = $current_week_users > 0 ? 100 : 0; // Handle division by zero and edge cases
}
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="row mt-4">
                <div class="col-lg-12 position-relative z-index-2">
                    <div class="card card-plain mb-4">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="d-flex flex-column h-100">
                                        <h2 class="font-weight-bolder mb-0">General Statistics</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                    <div class="col-lg-3 col-sm-5">
                            <div class="card mb-2">
                                <div class="card-header p-3 pt-2">
                                    <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">insert_invitation</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize">Pending Order</p>
                                        <h4 class="mb-0"><?= $pendingBookings; ?></h4>
                                    </div>
                                </div>
                                <hr class="dark horizontal my-0">
                                <div class="card-footer p-3">
                                    <p class="mb-0"><span class="text-success text-sm font-weight-bolder"><?= $percentage_change; ?>%</span> than last week</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-5">
                            <div class="card mb-2">
                                <div class="card-header p-3 pt-2">
                                    <div class="icon icon-lg icon-shape bg-gradient-info shadow-dark shadow text-center border-radius-xl mt-n4 position-absolute" >
                                        <i class="material-icons opacity-10">insert_invitation</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize">Completed Order</p>
                                        <h4 class="mb-0"><?= $allBookings; ?></h4>
                                    </div>
                                </div>
                                <hr class="dark horizontal my-0">
                                <div class="card-footer p-3">
                                    <p class="mb-0"><span class="text-success text-sm font-weight-bolder"><?= $percentage_change; ?>%</span> than last week</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-5 mt-sm-0 mt-4">
                            <div class="card mb-2">
                                <div class="card-header p-3 pt-2 bg-transparent">
                                    <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">store</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize">Total Products</p>
                                        <h4 class="mb-0"><?= $countAllProduct; ?></h4>
                                    </div>
                                </div>
                                <hr class="horizontal my-0 dark">
                                <div class="card-footer p-3">
                                    <p class="mb-0"><span class="text-success text-sm font-weight-bolder"></span>Products</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-5">
                            <div class="card mb-2">
                                <div class="card-header p-3 pt-2">
                                    <div class="icon icon-lg icon-shape bg-gradient-danger shadow-dark shadow text-center border-radius-xl mt-n4 position-absolute">
                                        <i class="material-icons opacity-10">people</i>
                                    </div>
                                    <div class="text-end pt-1">
                                        <p class="text-sm mb-0 text-capitalize">Total Users</p>
                                        <h4 class="mb-0"><?= $allUsers; ?></h4>
                                    </div>
                                </div>
                                <hr class="dark horizontal my-0">
                                <div class="card-footer p-3">
                                    <p class="mb-0"><span class="text-success text-sm font-weight-bolder"><?= $percentage_change_users; ?>%</span> than last week</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid pt-4">
                        <div class="row ">
                            <div class="col-lg-6 col-sm-5 mt-sm-0 mt-4">
                                <div class="card text-center rounded p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <h6 class="mb-0">Monthly Sales Chart for <?= $current_year; ?></h6>
                                        <form method="POST" action="">
                                            <div class="d-flex align-items-centers">
                                                <select class="form-select" style="width: 200px; padding: 6px;" name="month" id="month" onchange="this.form.submit()">
                                                    <option value="">All Months</option>
                                                    <?php
                                                    $selected_month = isset($_POST['month']) ? $_POST['month'] : ''; // Ensure $selected_month is defined
                                                    foreach (range(1, 12) as $m) {
                                                        $month_value = str_pad($m, 2, '0', STR_PAD_LEFT);
                                                        $month_name = date('F', mktime(0, 0, 0, $m, 10));
                                                        $selected = ($selected_month == $month_value) ? 'selected' : '';
                                                        echo "<option value=\"$month_value\" $selected>$month_name</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </form>
                                    </div>
                                    <canvas id="monthlySalesChart" width="400" height="200"></canvas>
                                </div>
                            </div>

                            <?php if (!empty($selected_month)) : ?>
                            <div class="col-lg-6 col-sm-5 mt-sm-0 mt-4 ">
                                <div class="card text-center rounded p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <h6 class="mb-0">Daily Sales Chart for <?= date('F', mktime(0, 0, 0, intval($selected_month), 10)); ?> <?= $current_year; ?></h6>
                                    </div>
                                    <canvas id="dailySalesChart" width="400" height="200" style="margin-top: 1rem;"></canvas>
                                </div>
                            </div>
                            <?php else :?>
                            <div class="col-lg-6 col-sm-5 mt-sm-0 mt-4 ">
                                <div class="card text-center rounded p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <h6 class="mb-0">Daily Sales Chart for <?= date('F', mktime(0, 0, 0, intval($current_month), 10)); ?> <?= $current_year; ?></h6>
                                    </div>
                                    <canvas id="dailySalesChart" width="400" height="200" style="margin-top: 1rem;"></canvas>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php') ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctxMonthly = document.getElementById('monthlySalesChart').getContext('2d');
        // Plugin to set the background color of the chart
        var whiteBackgroundPlugin = {
            id: 'whiteBackground',
            beforeDraw: function(chart) {
                var ctx = chart.canvas.getContext('2d');
                ctx.save();
                ctx.fillStyle = 'white';
                ctx.fillRect(0, 0, chart.width, chart.height);
                ctx.restore();
            }
        };
        var monthlySalesChart = new Chart(ctxMonthly, {
            type: 'line',
            data: {
                labels: <?= json_encode($labels); ?>,
                datasets: [{
                    label: 'Total Sales (RM)',
                    data: <?= json_encode($data); ?>,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Total Sales (RM)'
                        },
                        beginAtZero: true
                    }
                }
            },
            plugins: [whiteBackgroundPlugin]
        });

        
            var ctxDaily = document.getElementById('dailySalesChart').getContext('2d');
            var dailySalesChart = new Chart(ctxDaily, {
                type: 'line',
                data: {
                    labels: <?= json_encode($daily_labels); ?>,
                    datasets: [{
                        label: 'Daily Sales (RM)',
                        data: <?= json_encode($daily_data); ?>,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Day of the Month'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Total Sales (RM)'
                            },
                            beginAtZero: true
                        }
                    }
                },
            plugins: [whiteBackgroundPlugin]
            });
    });
</script>
<script>
    alertify.set('notifier', 'position', 'top-right');
    <?php
    if (isset($_SESSION['message'])) { ?>

        alertify.success('<?= $_SESSION['message'] ?>');
    <?php
        unset($_SESSION['message']);
    } ?>
</script>