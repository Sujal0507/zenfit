<?php
require 'db.php';

// Function to delete a user from MongoDB
function deleteUser($id)
{
    global $collection;
    $deleteResult = $collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
    return $deleteResult->getDeletedCount();
}

// Handling user deletion
if (isset($_POST['delete_user'])) {
    $deleteUserId = $_POST['delete_user'];
    $deletedCount = deleteUser($deleteUserId);
    if ($deletedCount > 0) {
        echo "<script>alert('User deleted successfully!');</script>";
    } else {
        $_SESSION['error_message'] = 'Failed to delete user!';
    }
}

// Fetch all users from MongoDB
$users = $collection->find();

?>
<?php include('adminnav.html');?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - ZenFit Users</title>
    <link rel="stylesheet" href="css/auser.css">
</head>

<body style="margin-left: 0px;margin-right: 0px;margin-top: 0px;">
    <div class="wrapper">
        <h1>Admin Panel - ZenFit Users</h1>
        <?php
        if (isset($_SESSION['error_message'])) {
            echo '<div class="error">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']); // Clear the session variable
        }
        ?>

        <table>
            <tr>
                <th>Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Contact Number</th>
                <th>Date of Joining</th>
                <th>Email</th>
                <th>Health Issues</th>
                <th>Action</th>
            </tr>
            <?php foreach ($users as $user) : ?>
                <tr>
                    <td><?= $user['name'] ?></td>
                    <td><?= $user['age'] ?></td>
                    <td><?= $user['gender'] ?></td>
                    <td><?= $user['phone'] ?></td>
                    <td><?= $user['joining_date'] ?></td>
                    <td><?= $user['email'] ?></td>
                    <td><?= $user['health_issues'] ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="delete_user" value="<?= $user['_id'] ?>">
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>

</html>
