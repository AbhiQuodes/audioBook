<?php
// Database connection
include "./../userAuthentication/config.php";
// Fetch song IDs for dropdown
$songOptions = "";
$sql = "SELECT id FROM musicsongs";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $songOptions .= "<option value='{$row['id']}'>{$row['id']}</option>";
    }
}

// Fetch song details when an ID is selected
$songData = [
    "title" => "",
    "artist" => "",
    "category" => "",
    "album" => "",
    "file_path" => "",
    "duration" => "",
    "lyrics" => "",
    "releasedate" => ""
];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['song_id'])) {
    $song_id = intval($_POST['song_id']);
    $query = "SELECT * FROM musicsongs WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $song_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $songData = $result->fetch_assoc();
    }
    $stmt->close();
}

// Update song details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_song'])) {
    $song_id = intval($_POST['song_id']);
    $title = $_POST["title"] ?? "";
    $artist = $_POST["artist"] ?? "";
    $category = $_POST["category"] ?? "";
    $album = $_POST["album"] ?? "";
    $file_path = $_POST["file_path"] ?? "";
    $duration = $_POST["duration"] ?? "";
    $lyrics = $_POST["lyrics"] ?? "";
    $releasedate = $_POST["release_date"] ?? "";

    $updateQuery = "UPDATE musicsongs SET 
        title = ?, artist = ?, category = ?, album = ?, file_path = ?, 
        duration = ?, lyrics = ?, release_date = ? WHERE id = ?";

    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param(
        "ssssssssi",
        $title,
        $artist,
        $category,
        $album,
        $file_path,
        $duration,
        $lyrics,
        $releasedate,
        $song_id
    );

    if ($stmt->execute()) {
        echo "<script>alert('Song updated successfully!'); window.location.href='viewSong.php';</script>";
    } else {
        echo "<script>alert('Error updating song!');</script>";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta title="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Song</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="operationInterface.php">Operations</a></li>
                <li><a href="viewSong.php">View</a></li>
                <li><a href="addSong.php">Add</a></li>
                <li><a href="updateSong.php" class="active">Update</a></li>
                <li><a href="deleteSong.php">Delete</a></li>
                <li><a href="#" id="logoutBtn">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Update Song</h2>

        <!-- Select Song ID -->
        <form method="POST">
            <label for="song_id">Select Song ID:</label>
            <select name="song_id" id="song_id">
                <option value="" disabled selected>Choose a song ID</option>
                <?= $songOptions ?>
            </select>
            <button type="submit">Fetch Song</button>
        </form>

        <!-- Update Song Form -->
        <?php if (!empty($songData["title"])): ?>
            <form method="POST">
                <input type="hidden" name="song_id" value="<?= htmlspecialchars($_POST['song_id']) ?>">

                <label>Title:</label>
                <input type="text" name="title" value="<?= htmlspecialchars($songData['title']) ?>" required>

                <label>Artist:</label>
                <input type="text" name="artist" value="<?= htmlspecialchars($songData['artist']) ?>">

                <label>Category:</label>
                <select title="category" required>
                    <option value="Trending" <?= $songData['category'] == "Trending" ? "selected" : "" ?>>Trending</option>
                    <option value="Romantic" <?= $songData['category'] == "Romantic" ? "selected" : "" ?>>Romantic</option>
                    <option value="Pop" <?= $songData['category'] == "Pop" ? "selected" : "" ?>>Pop</option>
                    <option value="Rock" <?= $songData['category'] == "Rock" ? "selected" : "" ?>>Rock</option>
                    <option value="Hip-Hop" <?= $songData['category'] == "Hip-Hop" ? "selected" : "" ?>>Hip-Hop</option>
                    <option value="Jazz" <?= $songData['category'] == "Jazz" ? "selected" : "" ?>>Jazz</option>
                    <option value="Classical" <?= $songData['category'] == "Classical" ? "selected" : "" ?>>Classical</option>
                </select>

                <label>Album:</label>
                <input type="text" name="album" value="<?= htmlspecialchars($songData['album'] ?? 'null') ?>">

                <label>File Path:</label>
                <input type="text" name="file_path" value="<?= htmlspecialchars($songData['file_path']) ?>" required>

                <label>Duration:</label>
                <input type="text" name="duration" value="<?= htmlspecialchars($songData['duration'] ?? 'null') ?>">

                <label>Lyrics:</label>
                <textarea name="lyrics"><?= htmlspecialchars($songData['lyrics'] ?? 'null') ?></textarea>

                <label>Release Date:</label>
                <input type="date" name="release_date" value="<?= htmlspecialchars($songData['releasedate'] ?? '') ?>">

                <button type="submit" name="update_song">Update Song</button>
            </form>
        <?php endif; ?>
    </main>
    <div class="login-wrapper" style="display:none;">
        <a href="./../userAuthentication/Login.php" class="login-btn">Login</a>
        <br>
        <p class="login-message">Please login to access this feature</p>
    </div>

    <script>
        document.getElementById("logoutBtn").addEventListener("click", function() {
            localStorage.removeItem("city");
            localStorage.removeItem("dob");
            localStorage.removeItem("email");
            localStorage.removeItem("pincode");
            localStorage.removeItem("userId");
            localStorage.removeItem("username");
            window.location.href = './../userAuthentication/login.php';
        });
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