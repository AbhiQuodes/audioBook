<?php
// Database connection
include "./../userAuthentication/config.php";

// Check if delete button was clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    $delete_sql = "DELETE FROM musicsongs WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        echo "<script>alert('Song deleted successfully!'); window.location.href='deleteSong.php';</script>";
    } else {
        echo "<script>alert('Error deleting song!');</script>";
    }

    $stmt->close();
}

// Fetch all songs from the database
$sql = "SELECT * FROM musicsongs";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Songs</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="operationInterface.php">Operations</a></li>
                <li><a href="viewSong.php">View</a></li>
                <li><a href="addSong.php">Add</a></li>
                <li><a href="updateSong.php">Update</a></li>
                <li><a href="deleteSong.php" class="active">Delete</a></li>
                <li><a href="#" id="logoutBtn">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Delete Songs</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Artist</th>
                    <th>Album</th>
                    <th>Category</th>
                    <th>File Path</th>
                    <th>Duration</th>
                    <th>Release Date</th>
                    <th>Uploaded Date</th>
                    <th>Lyrics</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row["id"]) ?></td>
                            <td><?= htmlspecialchars($row["title"]) ?></td>
                            <td><?= htmlspecialchars($row["artist"]) ?></td>
                            <td><?= htmlspecialchars($row["album"]) ?></td>
                            <td><?= htmlspecialchars($row["category"]) ?></td>
                            <td><?= htmlspecialchars($row["file_path"]) ?></td>
                            <td><?= htmlspecialchars($row["duration"]) ?></td>
                            <td><?= htmlspecialchars($row["release_date"]) ?></td>
                            <td><?= htmlspecialchars($row["uploaded_at"]) ?></td>
                            <td><?= htmlspecialchars($row["lyrics"]) ?></td>
                            <td>
                                <form method="POST" onsubmit="return confirm('Are you sure you want to delete this song?');">
                                    <input type="hidden" name="delete_id" value="<?= htmlspecialchars($row["id"]) ?>">
                                    <button type="submit" class="delete-btn">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="11">No songs found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="login-wrapper" style="display:none;">
            <a href="./../userAuthentication/Login.php" class="login-btn">Login</a>
            <br>
            <p class="login-message">Please login to access this feature</p>
        </div>
    </main>

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