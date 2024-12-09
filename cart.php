<?php
include 'db.php';
session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION['userid'])) {
    header("location: index.php");
    exit;
}

$userId = $_SESSION['userid'];
// Query to get all products in the cart for the current user
$query = "SELECT products.*, cart.quantity FROM products JOIN cart ON products.id = cart.product_id WHERE cart.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/landing.css">
    <title>Your Cart</title>
</head>

<body>
    <div class="header">
        <a href="landing.php" class="cart-link">Shop</a>
        <form action="logout.php" method="post" class="logout-form">
            <button class="signout" type="submit">Sign Out</button>
        </form>
    </div>
    <h1>Your Shopping Cart</h1>
    <div class="products">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $subtotal = $row["price"] * $row["quantity"];
                $total += $subtotal;

                echo "<div class='product'>";
                echo "<img src='" . $row["image"] . "' alt='" . $row["name"] . "'>";
                echo "<h3>" . $row["name"] . "</h3>";
                echo "<p>Quantity: " . $row["quantity"] . "</p>";
                echo "<p>Subtotal: $" . $subtotal . "</p>";
                echo "<form action='removeFromCart.php' method='post'>";
                echo "<input type='hidden' name='productId' value='" . $row["id"] . "'>";
                echo "<button type='submit' class='remove-item'>Remove</button>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "<p>Your cart is empty</p>";
        }
        ?>
    </div>
    <?php
    // Display the total when there are items in the cart
    if ($result->num_rows > 0) {
        echo "<div class='total-display'><h2>Total: $" . $total . "</h2></div>";
    }
    $conn->close();
    ?>
</body>

</html>