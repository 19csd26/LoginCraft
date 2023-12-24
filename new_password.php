<?php
session_start();
// Check if the user has a valid reset_email session variable
if (!isset($_SESSION["reset_email"])) {
    // If not, redirect to the forgot_password.php page
    header("Location: forgot_password.php");
    exit();
}
require_once "database.php";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["set_password"])) {
    $newPassword = $_POST["new_password"];
    $confirmPassword = $_POST["confirm_password"];

    if ($newPassword !== $confirmPassword) {
        $error_message = "Passwords do not match.";
    } else {
        // Retrieve email from the session
        $email = $_SESSION["reset_email"];

        // Update the user's password and clear reset token
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateSql = "UPDATE users SET password = ? WHERE email = ?";
        $updateStmt = mysqli_stmt_init($conn);

        // Prepare and execute the update statement
        if (mysqli_stmt_prepare($updateStmt, $updateSql)) {
            mysqli_stmt_bind_param($updateStmt, "ss", $hashedPassword, $email);
            mysqli_stmt_execute($updateStmt);

            // Password reset successful, redirect to login page
            header("Location: login.php");
            exit();
        } else {
            $error_message = "Error updating user record.";
        }

        mysqli_stmt_close($updateStmt);
    }
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <style>
        body {
            background-color: black;
            color: goldenrod;
        }
    </style>
    <div class="container">
        <h2>Set New Password</h2>
        <?php
        if (isset($error_message)) {
            echo "<div class='alert alert-danger'>$error_message</div>";
        }
        ?>
        <form action="new_password.php" method="post">
            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
            </div>
            <div class="form-btn">
                <input type="submit" value="Set Password" name="set_password" class="btn btn-primary">
            </div>
        </form>
    </div>
</body>

</html>