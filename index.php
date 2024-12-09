<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Since we're using prepared statements with parameter binding, we don't need to sanitize all the inputs. It's only required if we were to use user input within the HTML output.
    // Passwords are hashed, so we don't need to sanitize them
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $errorMessage = "";

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Invalid email format.";
    } else {
        $sql = "SELECT id, password FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Proceed if we find rows with the given email
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Check if the password matches the hash stored in the database
            if (password_verify($password, $row['password'])) {
                session_regenerate_id(); // Regenerate session ID to prevent fixation
                $_SESSION['loggedin'] = true; // Set the session as logged in
                $_SESSION['userid'] = $row['id']; // Store the user ID in the session
                header("location: landing.php"); // Redirect to the landing page
                exit;
            } else {
                $errorMessage = "Invalid email or password.";
            }
        } else {
            $errorMessage = "Invalid email or password.";
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/initial.css">
    <title>Log in</title>
</head>

<body>
    <div class="form-container">
        <form method="post" action="index.php">
            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required aria-required="true">
            <br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required aria-required="true">
            <br>

            <input type="submit" value="Log in">
        </form>
        <?php if (!empty($errorMessage)): ?>
            <p class="error-message">
                <?php echo $errorMessage; ?>
            </p>
        <?php endif; ?>
        <p>Don't have an account? <a href="signup.php">Sign up</a>.</p>
    </div>
</body>

</html>