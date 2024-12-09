<?php
session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: index.php");
    exit;
}

include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/landing.css">
    <title>Shop</title>
    <script>
        // Sends a POST request to addToCart.php with the product ID when the "Add to Cart" button is clicked
        function addToCart(productId) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                // If the request is successful, show an alert with the response text
                if (this.readyState == 4 && this.status == 200) {
                    alert(this.responseText);
                }
            };
            xhttp.open("POST", "addToCart.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("productId=" + productId);
        }
    </script>
</head>

<body>
    <div class="header">
        <a href="cart.php" class="cart-link">Cart</a>
        <form action="logout.php" method="post" class="logout-form">
            <button class="signout" type="submit">Sign Out</button>
        </form>
    </div>
    <div class="products">
        <?php
        $query = "SELECT * FROM products";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='product'>";
                echo "<img src='" . $row["image"] . "' alt='" . $row["name"] . "'>";
                echo "<h3>" . $row["name"] . "</h3>";
                echo "<p>" . $row["description"] . "</p>";
                echo "<p>$" . $row["price"] . "</p>";
                echo "<button class='cart-add' onclick='addToCart(" . $row["id"] . ")'>Add to Cart</button>";
                echo "</div>";
            }
        } else {
            echo "<p>No products found</p>";
        }
        $conn->close();
        ?>
    </div>
</body>

</html>