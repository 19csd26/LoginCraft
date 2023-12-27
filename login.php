<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Metadata for character set, viewport, and compatibility -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Title of the document -->
    <title>Login Form</title>

    <!-- Bootstrap CSS stylesheet from CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

    <!-- Custom CSS stylesheet (assuming you have a style.css file) -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Container for the login form -->
    <div class="container">

        <?php
        // Start the session
        session_start();

        // Check if the user is already logged in, redirect to index.php
        if (isset($_SESSION["login"])) {
            header("Location: registration.php");
            exit();
        }

        // Check if the login form is submitted
        if (isset($_POST["login"])) {
            // Get user input
            $email = $_POST["email"];
            $password = $_POST["password"];

            // Include the database connection file
            require_once "database.php";

            // Use prepared statements to prevent SQL injection
            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = mysqli_stmt_init($conn);

            if (mysqli_stmt_prepare($stmt, $sql)) {
                // Bind parameters and execute the query
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);

                // Get the result and fetch user details
                $result = mysqli_stmt_get_result($stmt);
                $user = mysqli_fetch_assoc($result);

                // Check if the user exists
                if ($user) {
                    // Verify the password
                    if (password_verify($password, $user["password"])) {
                        // Store user information in the session
                        $_SESSION["user"] = $user;
                        header("Location: index.php");
                        exit();
                    } else {
                        echo "<div class='alert alert-danger'>Password does not match</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Email does not exist</div>";
                }

                // Close the prepared statement
                mysqli_stmt_close($stmt);
            }

            // Close the database connection
            mysqli_close($conn);
        }
        ?>
        <!-- Login form -->
        <form action="login.php" method="post">
            <div class="container">
                 <h1>Log-In:</h1>
             </div>
            <div class="form-group">
                <input type="email" placeholder="Enter Email:" name="email" class="form-control">
            </div>
            <div class="form-group">
                <input type="password" placeholder="Enter Password:" name="password" class="form-control">
            </div>
            <div class="form-btn">
                <input type="submit" value="Login" name="login" class="btn btn-primary">
            </div>
        </form>

        <!-- Link to registration page for new users -->
        <div><p>Not registered yet?<a href="registration.php">Register Here</a></p></div>

        <!-- Link to Forgot Password page for users -->
        <div><p>Forgot Password! <a href="forgot_password.php">Click Here</a></p></div>
    </div>
</body>
</html>
