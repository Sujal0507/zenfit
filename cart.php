<?php
session_start();

require 'vendor/autoload.php';

use MongoDB\Client as MongoClient;

date_default_timezone_set('Asia/Kolkata');

$mongoClient = new MongoClient("mongodb://localhost:27017");
$db = $mongoClient->selectDatabase('zenfit');
$collection = $db->selectCollection('bills');
$cartsCollection = $db->selectCollection('cart');

function generateAndStoreBill($name, $cart, $total, $collection) {
    $billData = [
        'name' => $name,
        'cart' => $cart,
        'total' => $total,
        'timestamp' => new MongoDB\BSON\UTCDateTime(time() * 1000),
    ];

    $result = $collection->insertOne($billData);

    if ($result->getInsertedCount() > 0) {
        return $result->getInsertedId();
    } else {
        return false;
    }
}

function addOrUpdateCart($username, $cart, $cartsCollection) {
    $filter = ['username' => $username];
    $update = [
        '$set' => ['cart' => $cart],
        '$currentDate' => ['lastModified' => true]
    ];

    $options = ['upsert' => true];

    $result = $cartsCollection->updateOne($filter, $update, $options);

    if ($result->getModifiedCount() > 0) {
        return true;
    } else {
        return false;
    }
}

$billGenerated = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["generate_bill"])) {
    
    if(isset($_SESSION["name"])) {
        $username = $_SESSION["name"];
    } else {
        
        echo "Username not found in session.";
        exit;
    }

    $cart = $_SESSION["cart"];
    $total = 0;

    foreach ($cart as $product_id => $cart_item) {
        $subtotal = $cart_item["price"] * $cart_item["quantity"];
        $total += $subtotal;
    }

    $billId = generateAndStoreBill($username, $cart, $total, $collection);

    if ($billId) {
        $billGenerated = true;
    }

    // Update or add cart to database
    addOrUpdateCart($username, $cart, $cartsCollection);
}

// Function to update quantity in the cart
function updateQuantity($product_id, $new_quantity) {
    if (isset($_SESSION["cart"][$product_id])) {
        $_SESSION["cart"][$product_id]["quantity"] = $new_quantity;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_quantity"])) {
    $product_id = $_POST["product_id"];
    $new_quantity = $_POST["update_quantity"];
    updateQuantity($product_id, $new_quantity);
    header("Location: cart.php"); // Redirect to update the cart
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cart | Zenfit</title>
    <link rel="stylesheet" href="css/cart.css">
    <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
    <link rel="icon" href="assets/favicon.png" type="image/png" sizes="32x32">
    <link rel="icon" href="assets/favicon-192.png" type="image/png" sizes="192x192">
</head>
<body>
    <?php include('component/navbar.html'); ?>
    <style>
        #bill {
            color: #F2E9E9;
            border: none;
            padding: 15px;
            margin-top: -10px;
            font-size: 20px;
            font-weight: 500;
            cursor: pointer;
            background-color: #c11325;
            border-radius: 15px;
            margin-bottom: 200px;
        }

        #bill:hover {
            background: lightgray;
            color: black;
        }
    </style>
    <center><h1 style="margin-top: 30px;">Shopping Cart</h1></center>
    <div class="cart-container" style="margin-bottom: 200px;">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;

                if (isset($_SESSION["cart"]) && !empty($_SESSION["cart"])) {
                    foreach ($_SESSION["cart"] as $product_id => $cart_item) {
                        $subtotal = $cart_item["price"] * $cart_item["quantity"];
                        $total += $subtotal;
                        ?>
                        <tr>
                            <td><?= $cart_item["title"] ?></td>
                            <td>₹ <?= $cart_item["price"] ?></td>
                            <td>
                                <!-- Form for updating quantity -->
                                <form method="POST" action="">
                                    <input type="hidden" name="product_id" value="<?= $product_id ?>">
                                    <button type="submit" name="update_quantity" value="<?= $cart_item["quantity"] - 1 ?>" <?php if($cart_item["quantity"] <= 1) { echo "disabled"; } ?>>-</button>
                                    <span><?= $cart_item["quantity"] ?></span>
                                    <button type="submit" name="update_quantity" value="<?= $cart_item["quantity"] + 1 ?>">+</button>
                                </form>
                            </td>
                            <td>₹ <?= $subtotal ?></td>
                            <td>
                                <form method="POST" action="remove_product.php">
                                    <input type="hidden" name="product_id" value="<?= $product_id ?>">
                                    <button type="submit" name="remove_from_cart">Remove</button>
                                </form>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="5">Your cart is empty.</td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
        <div class="total" style="text-align: center;">
            <h2>Total: ₹ <?= $total ?></h2>
        </div>
    </div>
    <form method="POST">
        <center>
            <button type="submit" name="generate_bill" id="bill" onclick="showBillAmount(<?= $total ?>)">Checkout</button>
        </center>
    </form>

    <script>
        function showBillAmount(total) {
            alert("Bill generated successfully. Bill ID: <?= $billId ?>\nTotal Amount: ₹ " + total);
        }
    </script>

    <?php if ($billGenerated) { ?>
        <script>
            alert("Bill generated successfully. Bill ID: <?= $billId ?>\nTotal Amount: ₹ <?= $total ?>");
        </script>
    <?php } ?>

    <?php include('component/footers.html'); ?>
</body>
</html>
