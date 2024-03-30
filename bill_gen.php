<?php
session_start();

// Include the MongoDB PHP library (make sure you have it installed)
require 'vendor/autoload.php';

use MongoDB\Client as MongoClient;

// Set the timezone to Indian Standard Time (IST)
date_default_timezone_set('Asia/Kolkata');

// MongoDB connection setup
$mongoClient = new MongoClient("mongodb://localhost:27017");
$db = $mongoClient->selectDatabase('zenfit');
$collection = $db->selectCollection('bills');
// Query MongoDB to find the customer with the highest bill amount
$highestBill = $collection->findOne([], ['sort' => ['total' => -1]]);

// Extract customer information
$customerName = $highestBill['name'];
$highestAmount = $highestBill['total'];
// Function to delete a bill
function deleteBill($billId, $collection) {
    $result = $collection->deleteOne(['_id' => new MongoDB\BSON\ObjectID($billId)]);

    if ($result->getDeletedCount() > 0) {
        return true;
    } else {
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_bill"])) {
    $billIdToDelete = $_POST["bill_id"];

    if (deleteBill($billIdToDelete, $collection)) {
        // Bill deleted successfully, you can add a success message here if needed
    } else {
        // Failed to delete the bill, you can handle this case as needed
    }
}

// Query MongoDB to fetch all bills
$billsCursor = $collection->find();

// Convert cursor to array
$billsArray = iterator_to_array($billsCursor);

// Calculate monthly sales for each user
$monthlySales = [];

foreach ($billsArray as $bill) {
    $username = $bill['name'];
    $total = $bill['total'];

    if (!isset($monthlySales[$username])) {
        $monthlySales[$username] = 0;
    }

    $monthlySales[$username] += $total;
}

// Calculate daily sales
$dailySales = [];

foreach ($billsArray as $bill) {
    $timestamp = $bill['timestamp']->toDateTime()->format('Y-m-d');
    $total = $bill['total'];

    if (!isset($dailySales[$timestamp])) {
        $dailySales[$timestamp] = 0;
    }

    $dailySales[$timestamp] += $total;
}

// Prepare data for the pie chart
$labels = [];
$data = [];
$colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'];

foreach ($monthlySales as $username => $total) {
    $labels[] = $username;
    $data[] = $total;
}

// Prepare data for the bar chart
$barLabels = [];
$barData = [];

foreach ($dailySales as $date => $total) {
    $barLabels[] = $date;
    $barData[] = $total;
}

// Convert data to JSON for JavaScript
$labelsJson = json_encode($labels);
$dataJson = json_encode($data);
$colorsJson = json_encode($colors);

$barLabelsJson = json_encode($barLabels);
$barDataJson = json_encode($barData);
?>
<?php include('anavbar.html');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        /* Additional CSS for Highest Bill Amount Customer section */
.customer-info {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    margin-top: 20px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.customer-info p {
    margin: 5px 0;
}

.customer-info strong {
    font-weight: bold;
    color: #333;
}

.btn-back {
    display: inline-block;
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.btn-back:hover {
    background-color: #0056b3;
}

.bill-container {
    margin-bottom: 40px;
}

h1 {
    font-size: 24px;
    color: #333;
}

.report-container {
    background-color: #fff;
    border-radius: 8px;
    padding: 20px;
    margin-top: 20px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.delete-button {
    padding: 8px 12px;
    background-color: #dc3545;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.delete-button:hover {
    background-color: #c82333;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #f2f2f2;
    font-weight: bold;
    color: #555;
    text-transform: uppercase;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #f0f0f0;
}

    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Zenfit</title>
    <link rel="stylesheet" href="css/bill.css">
    <!-- Include Chart.js from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<h1>Bill Generated</h1> <br>
        <!-- Your existing table -->
        <table>
            <thead>
                <tr>
                    <th>Bill ID</th>
                    <th>Username</th>
                    <th>Total</th>
                    <th>Timestamp</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($billsArray as $bill) { ?>
                    <tr>
                        <td><?= $bill['_id'] ?></td>
                        <td><?= $bill['name'] ?></td>
                        <td>₹ <?= $bill['total'] ?></td>
                        <td><?= date('Y-m-d H:i:s', $bill['timestamp']->toDateTime()->getTimestamp()) ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="bill_id" value="<?= $bill['_id'] ?>">
                                <button type="submit" name="delete_bill" class="delete-button">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    
    
    <h3 style="
    padding-left: 200px;
">Sales Report According to Customer(With Amount)</h3>
    <div class="bill-container">
        <!-- Small or Medium Pie Chart -->
        <div class="chart-container">
            <canvas id="monthlySalesPieChart" width="400" height="400"></canvas>
        </div>
        <h3 style="
    padding-left: 200px;
">Monthly Sales Report</h3>
    <div class="bill-container">
        <!-- Daily Sales Bar Chart -->
        <div class="chart-container">
            <canvas id="dailySalesBarChart" width="400" height="400"></canvas>
        </div> <center>
        <div class="report-container">
        <!-- Your existing HTML with the updated Highest Bill Amount Customer section -->
<h1>Highest Bill Amount Customer</h1>
<div class="customer-info">
    <p><strong>Customer Name:</strong> <?= $customerName ?></p>
    <p><strong>Highest Bill Amount:</strong> ₹ <?= $highestAmount ?></p>
</div>

</center>
    </div>
    
    </div>

    <!-- JavaScript for Charts -->
    <script>
        // Pie Chart
        var pieData = {
            labels: <?= $labelsJson ?>,
            datasets: [{
                data: <?= $dataJson ?>,
                backgroundColor: <?= $colorsJson ?>
            }]
        };

        var pieOptions = {
            responsive: false,
            maintainAspectRatio: false
        };

        var pieChartCanvas = document.getElementById('monthlySalesPieChart').getContext('2d');
        new Chart(pieChartCanvas, {
            type: 'pie',
            data: pieData,
            options: pieOptions
        });

        // Bar Chart
        var barData = {
            labels: <?= $barLabelsJson ?>,
            datasets: [{
                label: 'Daily Sales',
                data: <?= $barDataJson ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };

        var barOptions = {
            responsive: false,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        };

        var barChartCanvas = document.getElementById('dailySalesBarChart').getContext('2d');
        new Chart(barChartCanvas, {
            type: 'bar',
            data: barData,
            options: barOptions
        });
    </script>
</body>
</html>
