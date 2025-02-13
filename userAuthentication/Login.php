<?php
// Connect to MySQL
$conn = new mysqli("localhost", "root", "");
session_start();

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

        function toggleForm(isSignup) {
            let loginForm = document.getElementById("loginForm");
            let signupForm = document.getElementById("signupForm");
            let formHeading = document.getElementById("formHeading");
            let toggleButton = document.getElementById("toggleButton");

            if (isSignup) {
                loginForm.style.display = "none";
                signupForm.style.display = "block";
                formHeading.innerText = "Sign-Up";
                toggleButton.innerText = "Already have an account? Login";
                toggleButton.setAttribute("onclick", "toggleForm(false)");
            } else {
                loginForm.style.display = "block";
                signupForm.style.display = "none";
                formHeading.innerText = "Login";
                toggleButton.innerText = "Don't have an account? Sign Up";
                toggleButton.setAttribute("onclick", "toggleForm(true)");
            }
        }
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background: #f8f8f8;
            padding: 0px;   
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 380px;
            margin: auto;
            position: relative;
            top:150px;
            min-width: 280px;

        }

        form{
            width:90%;
        }
        h2 {
            color: #333;
        }
        form input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background: #ce31dd;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            max-width: 300px;
            width: 100%;
            margin: auto;
            display: block;
            margin-top: 30px;
        }
        button:hover {
            background: #b020cc;
        }
        #toggleButton {
            margin-top: 10px;
            background: none;
            color: #ce31dd;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        #toggleButton:hover {
            text-decoration: underline;
        }
        @media(max-width :600px)
        {
            form{
                width:98%;
            }
            .container {
           
            top:100px;

        }
        }
    </style>
</head>
<body>

    <div class="container">
        <h2 id="formHeading">Login</h2>
        <?php if ($message) echo "<p style='color:red;'>$message</p>"; ?>

        <form id="loginForm" method="POST" style="box-shadow:0px 0px 0px black">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>

        <div id="signupForm" style="display:none;">
            <!-- <h2>Sign-Up</h2> -->
            <form method="POST" style="box-shadow:0px 0px 0px black">
                <input type="text" name="username" placeholder="Username" required><br>
                <input type="text" name="city" placeholder="City" required><br>
                <input type="text" name="pincode" placeholder="Pincode" required><br>
                <input type="password" name="password" placeholder="Password" required><br>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required><br>
                <button type="submit">Sign Up</button>
            </form>
        </div>

        <button id="toggleButton" onclick="toggleForm(true)">Don't have an account? Sign Up</button>
    </div>

</body>
</html>
