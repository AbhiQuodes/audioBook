<?php
// Connect to the database
include "./../userAuthentication/config.php";

// Fetch all songs from the musicsongs table
$sql = "SELECT * FROM musicsongs";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Songs</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="operationInterface.php">Operations</a></li>
                <li><a href="viewSong.php" class="active">View</a></li>
                <li><a href="addSong.php">Add</a></li>
                <li><a href="updateSong.php">Update</a></li>
                <li><a href="deleteSong.php">Delete</a></li>
                <li><a href="#" id="logoutBtn" onclick="logout()">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>All Songs</h2>
        <table border="1">
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
                <th>Play</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['artist']) ?></td>
                    <td><?= htmlspecialchars($row['album']) ?></td>
                    <td><?= htmlspecialchars($row['category']) ?></td>
                    <td><?= htmlspecialchars($row['file_path']) ?></td>
                    <td><?= htmlspecialchars($row['duration']) ?></td>
                    <td><?= htmlspecialchars($row['release_date']) ?></td>
                    <td><?= htmlspecialchars($row['uploaded_at']) ?></td>
                    <td><?= htmlspecialchars($row['lyrics']) ?></td>
                    <td>
                        <audio id="audioPlayer<?= $row['id'] ?>" controls>
                            <source src="<?= "/" . htmlspecialchars($row['file_path']) ?>" type="audio/mpeg">
                        </audio>



                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
        <div class="login-wrapper" style="display:none;">
            <a href="./../userAuthentication/Login.php" class="login-btn">Login</a>
            <br>
            <p class="login-message">Please login to access this feature</p>
        </div>
    </main>
    <script>
        function logout() {
            localStorage.removeItem('city');
            localStorage.removeItem('dob');
            localStorage.removeItem('email');
            localStorage.removeItem('pincode');
            localStorage.removeItem('userId');
            localStorage.removeItem('username');
            window.location.href = './../userAuthentication/login.php';
        }
         // Check if the user is logged in (exists in localStorage)
         let user_type = localStorage.getItem("user_type");
        let isLoggedIn = !!user_type; // Convert to boolean
        let loginWrapper = document.querySelector('.login-wrapper');

        if (user_type !="admin" && loginWrapper) {
            loginWrapper.style.display = "flex";
        }
        let currentAudio = null; // Track the currently playing audio

        document.addEventListener("play", function(event) {
            let audioPlayer = event.target;

            // Check if the event target is an <audio> element
            if (audioPlayer.tagName === "AUDIO") {
                // If another audio is playing, pause it first
                if (currentAudio && currentAudio !== audioPlayer && !currentAudio.paused) {
                    currentAudio.pause();
                }

                // Set the new current audio
                currentAudio = audioPlayer;

                // When the song finishes, reset currentAudio
                audioPlayer.onended = function() {
                    currentAudio = null;
                };
            }
        }, true);

        
    </script>
</body>

</html>

<?php
$conn->close();
?>