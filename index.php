<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

// Retrieve user information from the session
$userFullName = isset($_SESSION["user"]["full_name"]) ? $_SESSION["user"]["full_name"] : "User";
?><?php
    // Function to read todo items from a file
    function readTodoList()
    {
        $file = 'todolist.txt';
        if (file_exists($file)) {
            $content = file_get_contents($file);
            if ($content !== false) {
                return unserialize($content);
            }
        }
        return [];
    }

    // Function to save todo items to a file
    function saveTodoList($todoList)
    {
        $file = 'todolist.txt';
        $content = serialize($todoList);
        file_put_contents($file, $content);
    }

    // Handling the form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['task'])) {
            $task = trim($_POST['task']);
            if (!empty($task)) {
                $todoList = readTodoList();
                $todoList[] = $task;
                saveTodoList($todoList);
            }
        }
    }

    // Handling todo item deletion
    if (isset($_GET['delete'])) {
        $deleteIndex = (int)$_GET['delete'];
        $todoList = readTodoList();
        if (isset($todoList[$deleteIndex])) {
            unset($todoList[$deleteIndex]);
            saveTodoList($todoList);
        }
    }

    // Displaying the todo list
    $todoList = readTodoList();
    ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>User Dashboard</title>
    <!-- Custom styles with background image -->
    <style>
        body {
            background-image: url('bg.jpg');
            background-size: cover;
            background-color: black;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            color: goldenrod;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div style="background-color: gray; color:goldenrod" class="container-fluid">
            <a class="navbar-brand" href="#">LoginCraft</a>
            <div class="navbar-collapse justify-content-end">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" style="color:goldenrod" href="http://localhost/New%20folder/PHP_Learning/login-register-main/login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" style="color:goldenrod" href="http://localhost/New%20folder/PHP_Learning/login-register-main/registration.php">Registration</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <h1>Welcome to Dashboard, <?php echo $userFullName; ?>!</h1>
        <a href="logout.php" class="btn btn-warning">Logout</a>
    </div>
    <div class="container">
        <h1>Todo List</h1>
        <form method="post">
            <label for="task">New Task:</label>
            <input type="text" id="task" name="task" required>
            <button type="submit">Add Task</button>
        </form>

        <ul>
            <?php
            foreach ($todoList as $index => $task) {
                echo "<li>{$task} <a href='?delete={$index}'>Delete</a></li>";
            }
            ?>
        </ul>
    </div>
</body>

</html>