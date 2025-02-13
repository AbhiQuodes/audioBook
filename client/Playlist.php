<?php
// Include database connection
include './../userAuthentication/config.php';

// Get song ID from URL
$songId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch song details
$sql = "SELECT * FROM musicSongs WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $songId);
$stmt->execute();
$result = $stmt->get_result();
$song = $result->fetch_assoc();

// If song not found, show an error
if (!$song) {
    echo "<p class='error-message'>Song not found</p>";
    exit;
}

// Get next and previous song IDs
$prevSongId = $songId > 1 ? $songId - 1 : null;
$nextSongId = $songId + 1; // Assuming auto-incremented IDs exist

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($song['title']) ?> - Play</title>
    <style>
        /* Page Layout */
        .wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: #f4f4f4;
            padding: 20px;
        }

        /* Music Controller */
        .music-controller {
            display: flex;
            flex-direction: column;
            align-items: center;
            background: #e0e0e0;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .controls {
            display: flex;
            align-items: center;
            justify-content: space-around;
            width: 100%;
            margin-top: 10px;
        }

        .control-btn {
            border: none;
            background: none;
            cursor: pointer;
        }

        /* Progress Bar */
        .progress-container {
            width: 100%;
            height: 5px;
            background: #ccc;
            border-radius: 5px;
            margin-top: 10px;
            position: relative;
        }

        .progress-bar {
            height: 100%;
            width: 0%;
            background: #666;
            border-radius: 5px;
            transition: width 0.2s;
        }

        /* Time Display */
        .time-info {
            display: flex;
            justify-content: space-between;
            width: 100%;
            font-size: 12px;
            color: #555;
        }

        .error-message {
            font-size: 18px;
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <!-- Your song card goes here -->
    
    <!-- Music Controller -->
    <div class="music-controller">
        <audio id="audioPlayer" src="<?= htmlspecialchars($song['file_path']) ?>"></audio>

        <div class="progress-container">
            <div class="progress-bar" id="progressBar"></div>
        </div>
        
        <div class="time-info">
            <span id="currentTime">0:00</span>
            <span id="totalTime">0:00</span>
        </div>

        <div class="controls">
            <?php if ($prevSongId): ?>
                <a href="song.php?id=<?= $prevSongId ?>" class="control-btn">⏮️</a>
            <?php endif; ?>

            <button id="playPauseBtn" class="control-btn">
                <img id="playPauseImg" src="./../assets/play.png" width="30" alt="Play">
            </button>

            <a href="song.php?id=<?= $nextSongId ?>" class="control-btn">⏭️</a>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const audioPlayer = document.getElementById("audioPlayer");
        const playPauseBtn = document.getElementById("playPauseBtn");
        const playPauseImg = document.getElementById("playPauseImg");
        const progressBar = document.getElementById("progressBar");
        const currentTimeEl = document.getElementById("currentTime");
        const totalTimeEl = document.getElementById("totalTime");

        // Play/Pause functionality
        playPauseBtn.addEventListener("click", function () {
            if (audioPlayer.paused) {
                audioPlayer.play();
                playPauseImg.src = "./../assets/pause.png";
            } else {
                audioPlayer.pause();
                playPauseImg.src = "./../assets/play.png";
            }
        });

        // Update progress bar
        audioPlayer.addEventListener("timeupdate", function () {
            let progress = (audioPlayer.currentTime / audioPlayer.duration) * 100;
            progressBar.style.width = progress + "%";

            // Update time display
            let currentMinutes = Math.floor(audioPlayer.currentTime / 60);
            let currentSeconds = Math.floor(audioPlayer.currentTime % 60);
            let totalMinutes = Math.floor(audioPlayer.duration / 60);
            let totalSeconds = Math.floor(audioPlayer.duration % 60);

            currentTimeEl.textContent = `${currentMinutes}:${currentSeconds < 10 ? "0" : ""}${currentSeconds}`;
            totalTimeEl.textContent = `${totalMinutes}:${totalSeconds < 10 ? "0" : ""}${totalSeconds}`;
        });

        // Load song duration
        audioPlayer.addEventListener("loadedmetadata", function () {
            let totalMinutes = Math.floor(audioPlayer.duration / 60);
            let totalSeconds = Math.floor(audioPlayer.duration % 60);
            totalTimeEl.textContent = `${totalMinutes}:${totalSeconds < 10 ? "0" : ""}${totalSeconds}`;
        });
    });
</script>

</body>
</html>
