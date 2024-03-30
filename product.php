<?php
session_start();

include_once("db_connection.php");

$products = $collection->find();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_to_cart"])) {
    $product_id = $_POST["product_id"];
    $product = $collection->findOne(["_id" => new MongoDB\BSON\ObjectId($product_id)]);

    if (!empty($product)) {
        if (!isset($_SESSION["cart"])) {
            $_SESSION["cart"] = [];
        }

        $_SESSION["cart"][$product_id] = [
            "title" => $product["title"],
            "price" => $product["price"],
            "quantity" => isset($_SESSION["cart"][$product_id]["quantity"]) ? $_SESSION["cart"][$product_id]["quantity"] + 1 : 1,
        ];

        // Display an alert message
        echo '<script>alert("Product added successfully to the cart!");</script>';
    }
}
?>

<html>
<head>

    <link rel="stylesheet" href="/css/product.css">
    <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
<link rel="icon" href="assets/favicon.png" type="image/png" sizes="32x32">
<link rel="icon" href="assets/favicon-192.png" type="image/png" sizes="192x192">
</head>
<title>Products</title>
<body>
    <?php include('component/navbar.html'); ?>
    
    <div class="poster">
    <img src="assets/productposter.png" alt="" srcset="" height="350px" width="1520px">
    
    </div>
    <div class="product-container">
        <?php foreach ($products as $product) : ?>
            <div class="product">
                <form method="POST">
                    <input type="hidden" name="product_id" value="<?= $product["_id"] ?>">
                    <img src="<?= $product["image"] ?>" alt="<?= $product["title"] ?>" width="100">
                    <h1><?= $product["title"] ?></h1>
                    <p><?= $product['description'] ?></p>
                    <h3>Price: ₹ <?= $product["price"] ?></h3>
                    <button type="submit" name="add_to_cart">Add to Cart</button>
                </form>
            </div>
        <?php endforeach; ?>
        <?php

include('component\footers.html');

?>
    </div>
</body>
</html>
