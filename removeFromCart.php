<?php
session_start();
include 'db.php';

// Redirect to login page if not logged in
if (!isset($_SESSION['userid'])) {
    header("location: index.php");
    exit;
}

$userId = $_SESSION['userid'];
$productId = $_POST['productId'];

// Check the current quantity of the item in the cart
$stmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
$stmt->bind_param("ii", $userId, $productId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $quantity = $row['quantity'] - 1;

    // Update the quantity in the cart if still items left
    if ($quantity > 0) {
        $updateStmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $updateStmt->bind_param("iii", $quantity, $userId, $productId);
        $updateStmt->execute();
        $updateStmt->close();
    } else {
        // If quantity becomes 0, delete the entry
        $deleteStmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $deleteStmt->bind_param("ii", $userId, $productId);
        $deleteStmt->execute();
        $deleteStmt->close();
    }
}
$stmt->close();
$conn->close();

// Redirect back to the cart page
header("Location: cart.php");
exit;
