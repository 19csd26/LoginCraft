<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php
        if (isset($_POST["submit"])) {
           $fullName = $_POST["fullname"];
           $email = $_POST["email"];
           $password = $_POST["password"];
           $passwordRepeat = $_POST["repeat_password"];
           
           //to apply algorithm to use for hashing(Bcrypt) in password
           $passwordHash = password_hash($password, PASSWORD_DEFAULT);

           $errors = array();
           
           if (empty($fullName) OR empty($email) OR empty($password) OR empty($passwordRepeat)) {
            array_push($errors,"All fields are required");
           }
           if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Email is not valid");
           }
           //to check that password contains 8 character or not
           if (strlen($password)<8) {
            array_push($errors,"Password must be at least 8 charactes long");
           }
           // to check that the repeat password and entered password is same!!!
           if ($password!==$passwordRepeat) {
            array_push($errors,"Password does not match");
           }

           // it is used to embed PHP code from another file.
           require_once "database.php";

           //to check the entered email is present in the db to not
           $sql = "SELECT * FROM users WHERE email = '$email'";
           $result = mysqli_query($conn, $sql);
           $rowCount = mysqli_num_rows($result);
           if ($rowCount>0) {
            array_push($errors,"Email already exists!");
           }
           if (count($errors)>0) {
            foreach ($errors as  $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
           }else{
            //insert the data into database
            //SQL query to insert the data
            $sql = "INSERT INTO users (full_name, email, password) VALUES ( ?, ?, ? )";
            //this function will initializ4e a statement and returns an object suitable for mysqli_stmt_prepare()
            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
            if ($prepareStmt) {
                mysqli_stmt_bind_param($stmt,"sss",$fullName, $email, $passwordHash);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt); // Close the statement
                mysqli_close($conn); // Close the connection
                echo "<div class='alert alert-success'>You are registered successfully.</div>";
                // Redirect the user to the login page
                header("Location: login.php");
                exit();
            }else{
                die("Registration failed. Please try again.");
            }
           }     
        }
        ?> 
        <form action="registration.php" method="post">
          <div class="container">
             <h1 style="color:#ffb703">Registration:</h1>
          </div>
            <div class="form-group">
                <input type="text" class="form-control" name="fullname" placeholder="Full Name:" >
            </div>
            <div class="form-group">
                <input type="emamil" class="form-control" name="email" placeholder="Email:">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password:">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password:">
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Register" name="submit">
            </div>
        </form>
        <div>
        <div><p>Already Registered <a href="login.php">Login Here</a></p></div>
      </div>
    </div>
</body>
</html>