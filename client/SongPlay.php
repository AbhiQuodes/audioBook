<?php
// Include database connection
include './../userAuthentication/config.php';

// Get song ID from URL
$songId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch song details from the database
$sql = "SELECT id, title, artist, album,  file_path FROM musicSongs WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $songId);
$stmt->execute();
$result = $stmt->get_result();
$song = $result->fetch_assoc();

// If song not found, show an error and exit
if (!$song) {
    echo "<p class='error-message'>Song not found</p>";
    exit;
}

// Handle missing values
$title = htmlspecialchars($song['title'] ?? 'Unknown Title');
$artist = htmlspecialchars($song['artist'] ?? 'Unknown Artist');
$album = htmlspecialchars($song['album'] ?? 'Unknown Album');
$release_year = htmlspecialchars($song['release_year'] ?? 'Unknown Year');
$file_path = htmlspecialchars($song['file_path'] ?? '');

// Get next and previous song IDs
$prevSongId = $songId > 1 ? $songId - 1 : null;

// Check if the next song exists
$nextSql = "SELECT id FROM musicSongs WHERE id > ? ORDER BY id ASC LIMIT 1";
$nextStmt = $conn->prepare($nextSql);
$nextStmt->bind_param("i", $songId);
$nextStmt->execute();
$nextResult = $nextStmt->get_result();
$nextRow = $nextResult->fetch_assoc();
$nextSongId = $nextRow ? $nextRow['id'] : null;


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Play</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="./Home.css">

    <style>
        /* Page Layout */
        body::before {
            /* background: transparent; */
        }

        .wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: calc(100vh - 80px);
            padding: 0px 20px;
            color: #3d3636;
        }

        .head-wrapper {
            margin-bottom: 0px;
        }

        audio {
            width: 100%;
            height: 54px;
        }

        /* Image Box */
        .image-box {
            width: 100%;
            max-width: 400px;
            height: 250px;
            background: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            overflow: hidden;
        }

        .image-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            mix-blend-mode: multiply;
        }

        /* Music Controller */
        .music-controller {
            display: flex;
            flex-direction: column;
            align-items: center;
            background: rgb(224, 224, 224);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            gap: 10px;
            max-width: 400px;
            max-height: 500px;
            ;
            margin-top: 10px;
        }

        .controls {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 67%;
            margin-top: 10px;
            position: relative;
            top: -160px;
            scale: 1.3;
        }

        .control-btn {
            border: none;
            background: none;
            cursor: pointer;
        }

        .error-message {
            font-size: 18px;
            color: red;
            text-align: center;
        }

        /* Song Details */
        .song-details {
            text-align: center;
            font-size: 14px;
            color: #333;
        }

        .download-btn {
            display: inline-block;
            width: 100%;
            max-width: 250px;
            background: rgb(27, 27, 28);
            /* Blue color */
            color: white;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            position: relative;
            top: 20px;
            padding: 10px 15px;
            border-radius: 10px;
            text-decoration: none;
            transition: 0.3s ease-in-out;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .download-btn:hover {
            background: rgb(0, 0, 0);
            /* Darker blue on hover */
            transform: scale(1.05);
        }

        .download-btn:active {
            background: rgb(6, 6, 6);
            transform: scale(0.95);
        }
    </style>
</head>

<body>

    <div class="head-wrapper">
        <div class="head-box">
            <a href="./../client/home.php">
                <img class="brand-name" src=".\..\images\musicLogo.png" alt="brand-logo"></img>
            </a>

            <div class="head-tool">
                <ul class="head-list">
                    <li><a class="head-list-link-item" href="./../client/search.html">Search</a> </li>
                    <li><a class="head-list-link-item" href="./../client/PlayList.html
">Playlist </a> </li>
                    <li><a class="head-list-link-item" id="downloadLink" href="#">Download</a></li>
                </ul>
                <div class="address-box">
                    <div class="address-box-content">
                        <p class="address-box-location">Igatpuri</p>
                        <p class="address-box-pincode">422403</p>
                    </div>
                    <img
                        class="drop-down-icon"
                        src=".\..\images\Vector11.svg"
                        alt="drop-down-icon"></img>

                    <span class="material-symbols-outlined address-icon" style="color:rgb(207, 199, 192);">
                        location_on
                    </span>
                </div>
                <div class="profile">
                    <span class="material-symbols-outlined" style=" color: rgb(207, 199, 192);">
                        manage_accounts</span>
                </div>
            </div>
        </div>
    </div>
    <!-- Side-bar-section -->
    <div class="background-wrapper">
    </div>
    <div class="side-bar">
        <img class="cancel-btn" src=".\..\images\closeIcon.svg" alt="cancel-btn">

        <div class="profile-box">
            <div class="profile">
                <img src=".\..\images\carbon_user.svg" class="profile-user-icon" alt="profile-icon">
            </div>
            <div class="profile-user-details">
                <p class="profile-user-name" id="profileName">Loading...</p>
                <p class="basic-detail" id="profileDetails">Loading...</p>
            </div>
        </div>

        <ul class="side-bar-list">
            <li class="side-bar-list-item">
                <a class="side-bar-list-item-content" href="/">
                    <p style="text-align:left;"> <a href="./../client/Download.php" class="list-item-name">My Downloads</a></p>
                </a>
            </li>
            <li class="side-bar-list-item">
                <a class="side-bar-list-item-content" href="/">
                    <p style="text-align:left;"> <a href="./../client/PlayList.html
" class="list-item-name">Playlist</a></p>
                </a>
            </li>

        </ul>

        <a href="/" class="about-box">
            <p class="list-item-name">About Music Song</p>
            <p class="basic-detail">Version 1.0.1</p>
        </a>

        <div class="log-out-box" id="logoutBtn">
            <p class="list-item-name">Logout</p>
        </div>
    </div>

    <!-- Music Controller -->
    <div class="wrapper">
        <div class="music-controller">
            <div class="image-box">
                <img src="./../images/musicCardImg1.webp" alt="Album Art">
            </div>
            <h3 style="width:200px; text-align:center;"><?= $title ?></h3>

            <!-- Audio Player -->
            <!-- <audio id="audioPlayer" controls>
                <source src="<?= "/" . $file_path ?>" type="audio/mpeg">
            </audio> -->
            <audio id="audioPlayer" controls>
                <source src="<?= "/" . htmlspecialchars($song['file_path']) ?>" type="audio/mpeg">
            </audio>

            <!-- Hidden Download Link -->
            <!-- Download Button -->
            <a id="downloadLink"
                href="<?= "/" . htmlspecialchars($song['file_path']) ?>"
                download="<?= htmlspecialchars($song['title']) ?>.mp3"
                onclick="recordDownload()"
                class="download-btn">
                ⬇️ Download
            </a>



            <!-- Controls -->
            <div class="controls">
                <?php if ($prevSongId): ?>
                    <a href="songPlay.php?id=<?= $prevSongId ?>" class="control-btn">⏮️</a>
                <?php endif; ?>

                <?php if ($nextSongId): ?>
                    <a href="songPlay.php?id=<?= $nextSongId ?>" class="control-btn">⏭️</a>
                <?php endif; ?>
            </div>
            <!-- Song Details -->
            <div class="song-details">
                <p style="color:black; font-size:22px; text-align:left"><strong style="color:black">Artist:</strong> <?= $artist ?></p>
                <p style="color:black; font-size:22px; text-align:left"><strong style="color:black">Album:</strong> <?= $album ?></p>

            </div>
        </div>
    </div>
    <script>
        // Sidebar Profile Handling
        let profileBtn = document.querySelector(".profile");
        let sideBarContainer = document.querySelector(".side-bar");
        let fadeBackGroundContainer = document.querySelector(".background-wrapper");
        let closeSidebarBtn = document.querySelector(".cancel-btn");

        const userId = localStorage.getItem('userId');

        // Get the download link element
        const downloadLink = document.getElementById("downloadLink");

        // If userId exists, update the href dynamically
        if (userId) {
            downloadLink.href = `./../client/Download.php?userId=${encodeURIComponent(userId)}`;
        } else {
            downloadLink.href = "#"; // Stay on the same page if userId is missing
            downloadLink.addEventListener("click", function(event) {
                event.preventDefault();
                alert("User not found. Please log in.");
            });
        }
        let username = localStorage.getItem("username");

        if (username) {

            // If user is logged in, show profile details
            let dob = localStorage.getItem("dob") || "N/A";
            let city = localStorage.getItem("city") || "Unknown";

            document.querySelector(".profile-user-name").innerHTML = username;
            document.querySelector(".basic-detail").innerHTML = `DOB ${dob}  ${city}`;

            // Show Logout button
            let authBtn = document.querySelector(".log-out-box");
            authBtn.innerText = "Logout";
            authBtn.style.cursor = "pointer";

            authBtn.addEventListener("click", function() {
                localStorage.clear(); // Clears all stored data
                window.location.reload(); // Reload the page to reflect changes        window.location.href = "./../client/Home.php"; // Redirect to Home
            });
        } else {
            // If user is not logged in, show Login button

            document.querySelector(".profile-user-name").innerHTML = "Guest User";
            document.querySelector(".basic-detail").innerHTML = "Please log in.";
            let authBtn = document.querySelector(".log-out-box");
            authBtn.innerText = "Login";
            authBtn.style.cursor = "pointer";

            authBtn.addEventListener("click", function() {
                window.location.href = "./../userAuthentication/Login.php"; // Redirect to Login
            });
        }


        function recordDownload() {
            let userName = localStorage.getItem("username");
            let userDOB = localStorage.getItem("dob");
            let userCity = localStorage.getItem("city");
            let userPincode = localStorage.getItem("pincode");
            let userId = localStorage.getItem("userId");


            if (!userId || !userName || !userDOB || !userCity || !userPincode) {
                alert("User details not found. Please log in again.");
                return;
            }


            saveDownload(userId);
        }

        function saveDownload(userId) {
            let songId = <?= $songId ?>; // Song ID from PHP

            fetch("download.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        songId: songId,
                        userId: userId
                    })
                })
                .then(response => response.text())
                .then(data => console.log(data))
                .catch(error => console.error("Error saving download:", error));
        }
        profileBtn.addEventListener("pointerdown", () => {
            if (sideBarContainer.style.right != "0") {
                sideBarContainer.style.right = 0;
                fadeBackGroundContainer.style.display = "flex";
            }
        });
        closeSidebarBtn.addEventListener("pointerdown", () => {
            sideBarContainer.style.right = "-300px";
            fadeBackGroundContainer.style.display = "none";
        });
        fadeBackGroundContainer.addEventListener("pointerdown", () => {
            sideBarContainer.style.right = "-300px";
            fadeBackGroundContainer.style.display = "none";
        });
    </script>


</body>

</html>