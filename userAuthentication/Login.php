<?php
// Connect to MySQL
$conn = new mysqli("localhost", "root", "");
session_start();



// Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// Create Database if not exists
$sqlDB = "CREATE DATABASE IF NOT EXISTS userDB";
if ($conn->query($sqlDB) === TRUE) {
    $conn->select_db("userDB"); // Select the database
} else {
    die("Error creating database: " . $conn->error);
}

// Create Users Table if not exists
$sqlTable = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    city VARCHAR(100),
    pincode VARCHAR(10),
    password VARCHAR(255) NOT NULL
)";

if ($conn->query($sqlTable) !== TRUE) {
    die("Error creating table: " . $conn->error);
}

// Store login/signup messages
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn->select_db("userDB"); // Select database

    $username = $_POST["username"];
    $checkUser = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($checkUser);

    if ($result->num_rows > 0) {
        // Existing User (Login)
        $password = md5($_POST["password"]);
        $row = $result->fetch_assoc();

        if ($row["password"] == $password) {
            $_SESSION["username"] = $username;
            echo "<script>
                    localStorage.setItem('username', '$username');
                    alert('Login Successful');
                    window.location.href = './../client/Home.php';
                  </script>";
        } else {
            $message = "Incorrect Password!";
        }
    } else {
        // New User (Signup)
        if (isset($_POST["city"]) && isset($_POST["pincode"]) && isset($_POST["password"]) && isset($_POST["confirm_password"])) {
            $city = $_POST["city"];
            $pincode = $_POST["pincode"];
            $password = $_POST["password"];
            $confirm_password = $_POST["confirm_password"];

            if ($password !== $confirm_password) {
                $message = "Passwords do not match!";
            } else {
                $hashed_password = md5($password);
                $insertUser = "INSERT INTO users (username, city, pincode, password) VALUES ('$username', '$city', '$pincode', '$hashed_password')";
                if ($conn->query($insertUser) === TRUE) {
                    $_SESSION["username"] = $username;
                    echo "<script>
                            localStorage.setItem('username', '$username');
                            alert('Signup Successful');
                            window.location.href = './../client/Home.php';
                          </script>";
                } else {
                    $message = "Signup Failed!";
                }
            }
        } else {
            $message = "User not found. Please Sign Up.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Signup</title>
    <link href="./login.css" rel="stylesheet"> 
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            if (localStorage.getItem("username")) {
                window.location.href = "./../client/home.php";
            }
        });

        function toggleForm() {
            document.getElementById("loginForm").style.display = "none";
            document.getElementById("signupForm").style.display = "block";
        }
    </script>
</head>
<body>

    <h2>Login</h2>
    <?php if ($message) echo "<p style='color:red;'>$message</p>"; ?>
    <form id="loginForm" method="POST">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>

    <div id="signupForm" style="display:none;">
        <h2>Signup</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="text" name="city" placeholder="City" required><br>
            <input type="text" name="pincode" placeholder="Pincode" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required><br>
            <button type="submit">Sign Up</button>
        </form>
    </div>

    <button onclick="toggleForm()">Don't have an account? Sign Up</button>

</body>
</html>
