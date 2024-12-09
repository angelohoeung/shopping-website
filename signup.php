<?php
include 'db.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Ensure MySQLi throws exceptions for errors

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Since we're using prepared statements with parameter binding, we don't need to sanitize all the inputs. It's only required if we were to use user input within the HTML output.
    // Passwords are hashed, so we don't need to sanitize them
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phoneNumber = $_POST['phoneNumber'];
    $errorMessage = "";

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Invalid email format.";
    } else {
        try {
            $sql = "INSERT INTO users (firstName, lastName, email, password, phoneNumber) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $firstName, $lastName, $email, $password, $phoneNumber);
            $stmt->execute();
            $stmt->close();
            $successMessage = "New record created successfully.";
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) { // Duplicate entry
                $errorMessage = "A user with this email already exists. Please try a different email.";
            } else {
                $errorMessage = "An error occurred: " . $e->getMessage();
            }
        }
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
    <title>Sign up</title>
</head>

<body>
    <div class="form-container">
        <form method="post" action="signup.php">
            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" required aria-required="true">
            <br>

            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" required aria-required="true">
            <br>

            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required aria-required="true">
            <br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required aria-required="true">
            <br>

            <label for="phoneNumber">Phone Number:</label>
            <input type="tel" id="phoneNumber" name="phoneNumber" required aria-required="true">
            <br>

            <input type="submit" value="Sign up">
        </form>
        <?php if (!empty($errorMessage)): ?>
            <p class="error-message">
                <?php echo $errorMessage; ?>
            </p>
        <?php elseif (!empty($successMessage)): ?>
            <p class="success-message">
                <?php echo $successMessage; ?>
            </p>
        <?php endif; ?>
        <p>Already have an account? <a href="index.php">Log in</a>.</p>
    </div>
</body>

</html>