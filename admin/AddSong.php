<?php
// addSong.php
include './../userAuthentication/config.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $artist = $_POST['artist'];
    $category = $_POST['category'];
    $album = $_POST['album'];
    $file_name = $_POST['file_name'];
    $duration = $_POST['duration'];
    $lyrics = $_POST['lyrics'];
    $release_date = $_POST['release_date'];

    // Replace spaces with underscores
    $file_name = str_replace(' ', '_', $file_name);

    // Append "Music/musicLibrary/" to the file path
    $file_path = "Music/musicLibrary/" . $file_name;

    if (!empty($name) && !empty($category) && !empty($file_path)) {
        $sql = "INSERT INTO musicsongs (title, artist, category, album, file_path, duration, lyrics, release_date) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $name, $artist, $category, $album, $file_path, $duration, $lyrics, $release_date);

        if ($stmt->execute()) {
            echo "<script>alert('Song added successfully'); window.location.href='viewSong.php';</script>";
        } else {
            echo "<script>alert('Error adding song');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Please fill in required fields');</script>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Song</title>
    <link rel="stylesheet" href="./styles.css">
    <style>
        .required {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="operationInterface.php">Operations</a></li>
                <li><a href="viewSong.php">View</a></li>
                <li><a href="addSong.php" class="active">Add</a></li>
                <li><a href="updateSong.php">Update</a></li>
                <li><a href="deleteSong.php">Delete</a></li>
                <li><a href="#" id="logoutBtn" onclick="logout()">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Add Song</h2>
        <form method="POST">
            <label><span class="required">*</span> Name:</label>
            <input type="text" name="name" required><br>

            <label>Artist:</label>
            <input type="text" name="artist"><br>

            <label><span class="required">*</span> Category:</label>
            <select name="category" required>
                <option value="Trending">Trending</option>
                <option value="Romantic">Romantic</option>
                <option value="Pop">Pop</option>
                <option value="Rock">Rock</option>
                <option value="Hip-Hop">Hip-Hop</option>
                <option value="Jazz">Jazz</option>
                <option value="Classical">Classical</option>
            </select><br>

            <label>Album:</label>
            <input type="text" name="album"><br>

            <label><span class="required">*</span> File Name:</label>
            <input type="text" name="file_name" required><br> <!-- FIXED: Removed extra space -->

            <label>Duration:</label>
            <input type="text" name="duration"><br>

            <label>Lyrics:</label>
            <textarea name="lyrics"></textarea><br>

            <label>Release Date:</label>
            <input type="date" name="release_date"><br>

            <button type="submit">Add Song</button>
        </form>

        <div class="login-wrapper" style="display:none;">
            <a href="./../userAuthentication/Login.php" class="login-btn">Login</a>
            <br>
            <p class="login-message">Please login to access this feature</p>
        </div>
    </main>
    <script>
        function logout() {
            localStorage.removeItem("city");
            localStorage.removeItem("dob");
            localStorage.removeItem("email");
            localStorage.removeItem("pincode");
            localStorage.removeItem("userId");
            localStorage.removeItem("username");
            window.location.href = './../userAuthentication/login.php';
        }

        // Check if the user is logged in (exists in localStorage)
        let user_type = localStorage.getItem("user_type");
        let isLoggedIn = !!user_type; // Convert to boolean
        let loginWrapper = document.querySelector('.login-wrapper');

        if (user_type === "admin" && loginWrapper) {
            loginWrapper.style.display = "flex";
        }
    </script>
</body>

</html>