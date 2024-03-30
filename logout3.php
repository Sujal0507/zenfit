<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION["trainer"])) {
    // Destroy the session to log the user out
    session_destroy();
    
    // Display a logout success message using JavaScript
    echo "<script>alert('Logout successful.');</script>";
    // Redirect the user to the login page after a delay
    echo "<script>setTimeout(function() { window.location.href = 'signin.php'; }, 1500);</script>";
    // Exit PHP script to prevent further execution
    exit;
} else {
    // If user is not logged in, redirect to login page
    header("Location: tlogin.php");
    // Exit PHP script to prevent further execution
    exit;
}
?>

