<?php

session_start();
include('../config/dbcon.php');

function getAll($table)
{
    global $con;
    $query = "SELECT * FROM $table";
    return $query_run = mysqli_query($con, $query);
}

function getByID($table, $id)
{
    global $con;
    $query = "SELECT * FROM $table WHERE id='$id' ";
    return $query_run = mysqli_query($con, $query);
}


function redirect($url, $message)
{
    $_SESSION['message'] = $message;
    header('Location:' . $url);
    exit(0);
}

// myfunctions.php

// myfunctions.php

function getAllOrders($sort = 'desc') {
    global $con;
    $sortQuery = ($sort == 'asc') ? 'ASC' : 'DESC'; // Determine sort direction

    $query = "SELECT * FROM orders WHERE status ='0' ORDER BY created_at $sortQuery";
    $result = mysqli_query($con, $query);

    if (!$result) {
        die('Query failed: ' . mysqli_error($con));
    }

    return $result;
}


// myfunctions.php
function getOrderHistory($sort = 'desc', $status = null)
{
    global $con;

    // Build the SQL query based on parameters
    $query = "SELECT * FROM orders WHERE status != '0'";

    // Apply status filter if specified and not 'all'
    if ($status !== null && $status !== 'all') {
        $query .= " AND status = '$status'";
    }

    // Apply sorting
    $query .= " ORDER BY update_time $sort";

    // Execute the query
    $query_run = mysqli_query($con, $query);

    return $query_run;
}

function checkTrackingNoValid($trackingNo)
{
    global $con;

    $query = "SELECT * FROM orders WHERE tracking_no='$trackingNo' ";
    return mysqli_query($con, $query);
}

function getMonthlySales($year, $month = null)
{
    global $con;

    $months = [
        '01' => 'January', '02' => 'February', '03' => 'March',
        '04' => 'April', '05' => 'May', '06' => 'June',
        '07' => 'July', '08' => 'August', '09' => 'September',
        '10' => 'October', '11' => 'November', '12' => 'December'
    ];

    $query = "
        SELECT 
            DATE_FORMAT(created_at, '%Y-%m') AS month,
            SUM(total_price) AS total_sales
        FROM 
            orders
        WHERE 
            YEAR(created_at) = '$year' AND status = 1
    ";

    if ($month) {
        $query .= " AND MONTH(created_at) = '$month'";
    }

    $query .= "
        GROUP BY 
            DATE_FORMAT(created_at, '%Y-%m')
        ORDER BY 
            DATE_FORMAT(created_at, '%Y-%m')
    ";

    $result = mysqli_query($con, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($con));
    }

    $monthly_sales = array_fill_keys(array_keys($months), 0);

    while ($row = mysqli_fetch_assoc($result)) {
        $month_key = date('m', strtotime($row['month']));
        $monthly_sales[$month_key] = $row['total_sales'];
    }

    $output = [];
    foreach ($months as $num => $name) {
        $output[] = [
            'month' => "$year-$num",
            'total_sales' => $monthly_sales[$num]
        ];
    }

    return $output;
}

function getDailySales($year, $month)
{
    global $con;

    $query = "
        SELECT 
            DAY(created_at) AS day,
            SUM(total_price) AS total_sales
        FROM 
            orders
        WHERE 
            YEAR(created_at) = '$year' AND MONTH(created_at) = '$month' AND status = 1
        GROUP BY 
            DAY(created_at)
        ORDER BY 
            DAY(created_at)
    ";

    $result = mysqli_query($con, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($con));
    }

    $daily_sales = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $daily_sales[intval($row['day'])] = $row['total_sales'];
    }

    return $daily_sales;
}

function countAllUsers()
{
    global $con;
    $query = "SELECT COUNT(*) as total_users FROM users WHERE role_as ='0'";
    $query_run = mysqli_query($con, $query);
    
    if ($query_run) {
        $result = mysqli_fetch_assoc($query_run);
        return $result['total_users'];
    } else {
        return 0; // Return 0 if the query fails
    }
}

function countAllPending()
{
    global $con;
    $query = "SELECT COUNT(*) as total_orders FROM orders WHERE status='0'";
    $query_run = mysqli_query($con, $query);
    
    if ($query_run) {
        $result = mysqli_fetch_assoc($query_run);
        return $result['total_orders'];
    } else {
        return 0; // Return 0 if the query fails
    }
}

function countAllOrders()
{
    global $con;
    $query = "SELECT COUNT(*) as total_orders FROM orders WHERE status='1'";
    $query_run = mysqli_query($con, $query);
    
    if ($query_run) {
        $result = mysqli_fetch_assoc($query_run);
        return $result['total_orders'];
    } else {
        return 0; // Return 0 if the query fails
    }
}

function countOrdersForWeek($week_start, $week_end)
{
    global $con;
    $query = "SELECT COUNT(*) as total_orders FROM orders WHERE status ='1' AND created_at >= '$week_start' AND created_at <= '$week_end'";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $result = mysqli_fetch_assoc($query_run);
        return $result['total_orders'];
    } else {
        return 0; // Return 0 if the query fails
    }
}

function countUsersForWeek($week_start, $week_end)
{
    global $con;
    $query = "SELECT COUNT(*) as total_users FROM users WHERE role_as ='0' AND created_at >= '$week_start' AND created_at <= '$week_end'";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $result = mysqli_fetch_assoc($query_run);
        return $result['total_users'];
    } else {
        return 0; // Return 0 if the query fails
    }
}

function countAllProduct()
{
    global $con;
    $query = "SELECT COUNT(*) as total_orders FROM products WHERE status='0'";
    $query_run = mysqli_query($con, $query);
    
    if ($query_run) {
        $result = mysqli_fetch_assoc($query_run);
        return $result['total_orders'];
    } else {
        return 0; // Return 0 if the query fails
    }
}
