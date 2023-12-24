<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["users"])) {
    header("Location: login.php");
    exit();
}

// Retrieve user information from the session
$userFullName = $_SESSION["users"]["full_name"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <!-- Add your custom stylesheets if needed -->
</head>
<body>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header">
                        <h3>Welcome, <?php echo $userFullName; ?>!</h3>
                    </div>
                    <div class="card-body">
                        <p>This is your dashboard content.</p>
                        <!-- Add your dashboard content here -->
                    </div>
                    <div class="card-footer text-end">
                        <a href="logout.php" class="btn btn-danger">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
