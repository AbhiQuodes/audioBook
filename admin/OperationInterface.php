
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Operations</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="operationInterface.php" class="active">Operations</a></li>
                <li><a href="viewSong.php">View</a></li>
                <li><a href="addSong.php">Add</a></li>
                <li><a href="updateSong.php">Update</a></li>
                <li><a href="deleteSong.php">Delete</a></li>
                <li><a href="#" id="logoutBtn" onclick="logout()">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
    <div class="container">
        <h2>Welcome to Admin Panel</h2>
        <!-- <p>Select an operation from the navigation bar.</p> -->
    </div>
        <p style="text-align:center; font-size:22px; ">Select an Operation</p>
        <select id="operationDropdown" onchange="navigate()">
            <option value="">-- Select Operation --</option>
            <option value="addSong.php">Add Song</option>
            <option value="updateSong.php">Update Song</option>
            <option value="deleteSong.php">Delete Song</option>
        </select>
        <div class="login-wrapper" style="display:none;">
            <a href="./../userAuthentication/Login.php" class="login-btn">Login</a>
            <br>
            <p class="login-message">Please login to access this feature</p>
        </div>
    </main>

    <script>
        function navigate() {
            let dropdown = document.getElementById("operationDropdown");
            let selectedPage = dropdown.value;
            if (selectedPage) {
                window.location.href = selectedPage;
            }
        }

        function logout() {
            localStorage.removeItem("city");
            localStorage.removeItem("dob");
            localStorage.removeItem("email");
            localStorage.removeItem("pincode");
            localStorage.removeItem("userId");
            localStorage.removeItem("username");
            
            window.location.href = "./../userAuthentication/login.php";
        }
        // Check if the user is logged in (exists in localStorage)
        let user_type = localStorage.getItem("user_type");
        let isLoggedIn = !!user_type; // Convert to boolean
        let loginWrapper = document.querySelector('.login-wrapper');

        if (user_type !="admin" && loginWrapper) {
            loginWrapper.style.display = "flex";
        }
    </script>
</body>

</html>