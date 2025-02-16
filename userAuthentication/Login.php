<?php
session_start();

// Connect to MySQL
$conn = new mysqli("localhost", "root", "");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create Database if not exists
$sqlDB = "CREATE DATABASE IF NOT EXISTS userDB";
$conn->query($sqlDB);
$conn->select_db("userDB"); // Select the database

// Create Users Table if not exists
$sqlTable = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,  -- Making email unique
    city VARCHAR(100),
    pincode VARCHAR(10),
    dob DATE,
    user_type ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    password VARCHAR(255) NOT NULL
);

";

$conn->query($sqlTable);

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (!empty($email) && !empty($password)) {
        // Check if user exists
// Prepare SQL statement to fetch user details
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();



        

        if ($result->num_rows > 0) {
            // Existing User (Login)
            $row = $result->fetch_assoc();
            $hashed_password = md5($password); // Hash the user input
            if ($hashed_password === $row["password"]) {
                $_SESSION["username"] = $row["username"];
                $_SESSION["email"] = $row["email"];
                $_SESSION["dob"] = $row["dob"];
                $_SESSION["city"] = $row["city"];
                $_SESSION["pincode"] = $row["pincode"];
                $_SESSION["user_type"] = $row["user_type"];
                $_SESSION["userId"] = $row["id"];

                echo "<script>
                localStorage.setItem('userId', '" . $row['id'] . "');
                localStorage.setItem('email', '" . $row['email'] . "');
                localStorage.setItem('username', '" . $row['username'] . "');
                localStorage.setItem('dob', '" . $row['dob'] . "');
                localStorage.setItem('city', '" . $row['city'] . "');
                localStorage.setItem('user_type', '" . $row['user_type'] . "');
                localStorage.setItem('pincode', '" . $row['pincode'] . "');
                window.location.href = '" . (($row["user_type"] == 'admin') ? "./../admin/OperationInterface.php" : "./../client/home.php") . "';
            </script>";
            } else {
                $message = "Incorrect Password!";
            }
        } else {
            // New User (Signup)
            if (
                isset($_POST["email"]) && isset($_POST["city"]) && isset($_POST["pincode"]) && isset($_POST["dob"]) &&
                isset($_POST["password"]) && isset($_POST["confirm_password"]) && isset($_POST["user_type"])
            ) {
                $city = trim($_POST["city"]);
                $pincode = trim($_POST["pincode"]);
                $dob = $_POST["dob"];
                $confirm_password = $_POST["confirm_password"];
                $user_type = $_POST["user_type"];
                $email = $_POST["email"];
                $username = trim($_POST["username"]);



                if ($password !== $confirm_password) {
                    $message = "Passwords do not match!";
                } elseif ($user_type !== "admin" && $user_type !== "user") {
                    $message = "Invalid user type!";
                } else {
                    $hashed_password = md5($_POST["password"]);
                    $insertUser = "INSERT INTO users (username, email, city, pincode, dob, user_type, password) 
                    VALUES ('$username', '$email', '$city', '$pincode', '$dob', '$user_type', '$hashed_password')";

                      
                        
                    if ($conn->query($insertUser) === TRUE) {
                        $_SESSION["username"] = $username;
                        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
                        $stmt->bind_param("s", $email);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        if ($row = $result->fetch_assoc()) {
                            $userId = $row["id"];
                        }
                        else{
                            $userId = -1;

                        }
                        
                        $stmt->close();
                        $_SESSION["username"] = $row["username"];
                        $_SESSION["email"] = $row["email"];
                        $_SESSION["dob"] = $row["dob"];
                        $_SESSION["city"] = $row["city"];
                        $_SESSION["pincode"] = $row["pincode"];
                        $_SESSION["user_type"] = $row["user_type"];
                        $_SESSION["userId"] = $row["id"];
                        echo "<script>
                        localStorage.setItem('username', '$username');
                        localStorage.setItem('userId', '$userId');
                        localStorage.setItem('email', '$email');
                        localStorage.setItem('dob', '$dob');
                        localStorage.setItem('user_type', '$user_type');
                        localStorage.setItem('city', '$city');
                        localStorage.setItem('pincode', '$pincode');
                        window.location.href = '" . (($user_type == 'admin') ? "./../admin/OperationInterface.php" : "./../client/home.php") . "';
                        </script>";
                    } else {
                        if($conn->error == "Duplicate entry '$email' for key 'unique_email'")
                        {
                            $message ="Email id already Exist";
                        }
                        else{

                            $message = "Signup  Failed!";
                        }
                    }
                }
            } else {
                $message = "Please fill in all fields.";
            }
        }
    } else {
        $message = "email and Password are required!";
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
        ::-webkit-scrollbar {
            height: 0px;
            width: 0px;
        }

        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background: #f8f8f8;
        }

        .container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 380px;
            margin: auto;
            position: relative;
            top: 150px;
        }

        form input,
        select {
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
            width: 100%;
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
    </style>
</head>

<body>

    <div class="container">
        <h2 id="formHeading">Login</h2>
        <?php if ($message) echo "<p style='color:red;'>$message</p>"; ?>

        <!-- Login Form -->
        <form id="loginForm" method="POST">
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>

        <!-- Signup Form -->
        <div id="signupForm" style="display:none;">
            <form method="POST">
                <input type="text" name="username" placeholder="Username" required><br>
                <input type="email" name="email" placeholder="Email" required><br>
                <input type="text" name="city" placeholder="City" required><br>
                <input type="text" name="pincode" placeholder="Pincode" required><br>
                <input type="date" name="dob" required><br>
                <select name="user_type" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select><br>
                <input type="password" name="password" placeholder="Password" required><br>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required><br>
                <button type="submit">Sign Up</button>
            </form>
        </div>

        <button id="toggleButton" onclick="toggleForm(true)">Don't have an account? Sign Up</button>
    </div>

</body>

</html>