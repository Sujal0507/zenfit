<?php
include_once("db_connection.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product = [
        "title" => $_POST["title"],
        "price" => $_POST["price"],
        'description' => $_POST['description'],
        "image" => $_POST["image"],
    ];

    $insertResult = $collection->insertOne($product);

    if ($insertResult->getInsertedCount() > 0) {
        $message = "Product added successfully!";
    } else {
        $message = "Failed to add product.";
    }
}
?>

<html>
<head>
    <script>
        function displayMessage(message) {
            alert(message);
        }
    </script>
</head>
<body>
<link rel="stylesheet" href="css/addproduct.css">
<center><h1>Add Products</h1></center>
<form method="POST" action="addproduct.php" onsubmit="displayMessage('<?php echo $message; ?>')">
    <label>Title:</label>
    <input type="text" name="title"><br>
    <label>Price:</label>
    <input type="text" name="price"><br>
    <label for="description">Description:</label>
    <textarea name="description" required></textarea><br>
    <label>Image URL:</label>
    <input type="text" name="image"><br>
    <input type="submit" value="Add Product">
</form>
</body>
</html>
