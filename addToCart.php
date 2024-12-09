<?php
session_start();
include 'db.php';

// Check if the user is logged in and a product ID is provided
if (isset($_SESSION['userid']) && isset($_POST['productId'])) {
    $userId = $_SESSION['userid'];
    $productId = $_POST['productId'];

    // Check if the item is already in the cart
    $checkQuery = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ii", $userId, $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Item not in cart, insert it
    if ($result->num_rows == 0) {
        $insertQuery = "INSERT INTO cart (user_id, product_id) VALUES (?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("ii", $userId, $productId);
        $insertStmt->execute();
        $insertStmt->close();
    } else {
        // Item already in cart, update the quantity
        $updateQuery = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("ii", $userId, $productId);
        $updateStmt->execute();
        $updateStmt->close();
    }

    echo "Added to cart successfully";
    $stmt->close();
} else {
    echo "Error: User not logged in or product ID not provided";
}
$conn->close();
