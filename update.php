<?php
session_start();

require_once __DIR__ . '/vendor/autoload.php';
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$db = $mongoClient->zenfit;
$collection = $db->product;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_product"])) {
    $product_id = $_POST["product_id"];
    $title = $_POST["title"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $image = $_POST["image"];

    $updateResult = $collection->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($product_id)],
        ['$set' => [
            "title" => $title,
            "description" => $description,
            "price" => $price,
            "image" => $image,
        ]]
    );

    if ($updateResult->getModifiedCount() > 0) {
        $_SESSION["success_message"] = "Product updated successfully!";
    } else {
        $_SESSION["error_message"] = "Failed to update product!";
    }

    header("Location: manageproduct.php");
    exit;
}

if (isset($_POST["edit_product"])) {
    $product_id = $_POST["product_id"];
    $product = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($product_id)]);
} else {
    header("Location: manageproduct.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <link rel="stylesheet" href="css/updateproduct.css">
    <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
</head>
<body>
    <div class="form-container">
        <h2>Update Product</h2>
        <form method="POST">
            <input type="hidden" name="product_id" value="<?= $product["_id"] ?>">
            <label for="title">Title:</label><br>
            <input type="text" id="title" name="title" value="<?= $product["title"] ?>" required><br><br>

            <label for="description">Description:</label><br>
            <textarea id="description" name="description" rows="4" required><?= $product["description"] ?></textarea><br><br>

            <label for="price">Price (₹):</label><br>
            <input type="number" id="price" name="price" min="0" step="0.01" value="<?= $product["price"] ?>" required><br><br>

            <label for="image">Image URL:</label><br>
            <input type="text" id="image" name="image" value="<?= $product["image"] ?>" required><br><br>

            <button type="submit" name="update_product">Update Product</button>
        </form>
    </div>
</body>
</html>
