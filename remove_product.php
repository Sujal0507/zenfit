<?php
session_start();

require_once __DIR__ . '/vendor/autoload.php';
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$db = $mongoClient->five_Jewellers;
$collection = $db->cart;


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["remove_from_cart"])) {
    $product_id = $_POST["product_id"];

    // Remove the product from the MongoDB collection
    $collection->deleteOne(["product_id" => $product_id]);

    // Remove the product from the cart session as well
    unset($_SESSION["cart"][$product_id]);
}

// Redirect back to cart.php or any other page as needed
header("Location: cart.php");
exit;
?>
