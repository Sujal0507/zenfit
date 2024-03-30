<?php
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $productId = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_POST['image'];

    // Update the product in the database
    $updateResult = $collection->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($productId)],
        ['$set' => [
            'title' => $title,
            'description' => $description,
            'price' => (float)$price,
            'image' => $image,
        ]]
    );

    if ($updateResult->getModifiedCount() > 0) {
        // Product updated successfully
        header('Location: manageproduct.php');
        exit;
    } else {
        // Failed to update product, handle error
        echo "Failed to update product.";
    }
} else {
    // Redirect or handle error
    header('Location: manageproduct.php');
    exit;
}
?>
