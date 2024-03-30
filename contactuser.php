<?php
require_once __DIR__ . '/vendor/autoload.php';

// MongoDB connection
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");

// Selecting a database
$database = $mongoClient->zenfit;

// Selecting a collection
$collection = $database->users;

// Fetch all users
$users = $collection->find();

// Check if form is submitted for individual sends
if (isset($_POST['send_individual'])) {
    $subject = $_POST['subject'];

    // Send Email using PHPMailer for each selected user
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Update with your SMTP host
        $mail->SMTPAuth = true;
        $mail->Username = 'zenfit.india@gmail.com'; // SMTP username
        $mail->Password = 'xwrmfyoxlmsdwvfb'; // SMTP password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Email content
        $mail->setFrom('zenfit.india@gmail.com', 'Zenfit India');
        $mail->isHTML(true);

        // Loop through selected users and send message
        if (!empty($_POST['send_to'])) {
            foreach ($_POST['send_to'] as $userId) {
                $user = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($userId)]);
                if ($user) {
                    $email = $user['email'];
                    $message = $_POST['message_' . $userId];
                    $mail->addAddress($email, $user['name']);
                    $mail->Subject = $subject;
                    $mail->Body = $message;
                    $mail->send();
                    $mail->clearAddresses();
                }
            }
            echo '<div class="alert alert-success" role="alert">Messages sent successfully!</div>';
        } else {
            echo '<div class="alert alert-warning" role="alert">No users selected to send messages.</div>';
        }
    } catch (Exception $e) {
        echo '<div class="alert alert-danger" role="alert">Message could not be sent. Mailer Error: ' . $mail->ErrorInfo . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message to All Users</title>
    <!-- Bootstrap CSS Link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include('trainernav.html');?>
    <div class="container mt-5">
        <h2>Send Message to All Users</h2>

        <!-- Table to Display Users -->
        <form method="post">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Message</th>
                        <th>Send</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['name']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $user['phone']; ?></td>
                            <td>
                                <textarea class="form-control" name="message_<?php echo $user['_id']; ?>" rows="3"></textarea>
                            </td>
                            <td>
                                <input type="checkbox" name="send_to[]" value="<?php echo $user['_id']; ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Form to Send Message -->
            <div class="form-group">
                <label for="subject">Subject:</label>
                <input type="text" class="form-control" id="subject" name="subject" required>
            </div>

            <button type="submit" class="btn btn-primary" name="send_individual">Send Selected Messages</button>
        </form>
    </div>
</body>
</html>
