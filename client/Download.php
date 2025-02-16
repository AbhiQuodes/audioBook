<?php
include './../userAuthentication/config.php'; // Database connection

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

$songId = $data['songId'] ?? 0;
$userId = $data['userId'] ?? 0;

if ($songId && $userId) {
    $sql = "INSERT INTO downloads (song_id, user_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $songId, $userId);

    if ($stmt->execute()) {
        echo "Download recorded successfully.";
    } else {
        echo "Error recording download.";
    }
    exit;
}

?>
<?php
include './../userAuthentication/config.php'; // Database connection


// Retrieve userId from query parameters
$userId = isset($_GET['userId']) ? (int)$_GET['userId'] : 0;
// Check if userId is valid
if (!$userId) {

    echo '<div class="login-wrapper" style=" display: flex;
            justify-content: center;
            flex-direction: column;
            gap: 0px;
            align-items: center;
            width: 100vw;
            height: 100vh;
            top: 0;
            z-index: 30;
            left: 0;
            position: fixed;
            background-color: rgba(179, 167, 167, 0.61);
            box-shadow: 0px 0px 5px #000000;">';
    echo '<a href="./../userAuthentication/Login.php" class="login-btn" style="  display: inline-block;
             padding: 10px 20px;
            background: #ce31dd;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin-left: 10px;
            text-decoration: none;">Login</a>';
    echo '<br>';
    echo '<p class="error-message">User not found. Please log in.</p>';
    echo '</div>';
    exit;
}

// Fetch downloaded songs
$sql = "SELECT d.id AS download_id, m.id AS song_id, m.title, m.artist, d.downloaded_at 
        FROM downloads d
        JOIN musicSongs m ON d.song_id = m.id
        WHERE d.user_id = ?
        ORDER BY d.downloaded_at DESC";

$stmt = $conn->prepare($sql);

// Check if the statement was prepared successfully
if (!$stmt) {
    die("Error in SQL query: " . $conn->error); // Debugging message
}

$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$downloads = [];
while ($row = $result->fetch_assoc()) {
    $downloads[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Downloads</title>
    <link rel="stylesheet" href="./Home.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <style>
        body::before {
            background: transparent;
            ;
        }

        body {

            font-family: Arial, sans-serif;
            text-align: center;
        }

        .container {
            max-width: 600px;
            width: 100vw;
            margin: 20px auto;
            padding: 20px;
            background-color: #868686bf;
            overflow-y: auto;
            max-height: 600px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 20px;
        }

        .download-card {
            display: flex;
            align-items: center;
            background: #e0e0e0;

            padding: 10px;
            margin-bottom: 10px;
            border-radius: 6px;
            position: relative;
        }

        .song-image {
            width: 80px;
            height: 80px;
            border-radius: 6px;
            object-fit: cover;
        }

        .song-details {
            flex: 1;
            padding-left: 15px;
        }

        .delete-btn {
            background: red;
            color: white;
            border: none;
            padding: 6px 10px;
            cursor: pointer;
            border-radius: 4px;
            z-index: 20;
        }

        .delete-btn:hover {
            background: darkred;
        }

        .error-message {
            color: red;
            font-size: 18px;
        }
                /* Login Button */
                .login-btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #333;
            background-color: transparent;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
        }

        .login-btn:hover {
            background-color: rgb(219, 78, 212);
            /* Light gray background on hover */
        }

        /* Login Message */
        .login-message {
            margin-top: 8px;
            font-size: 14px;
            color: #bebebe;
            /* Medium gray */
        }

        .login-wrapper {
            display: flex;
            justify-content: center;
            flex-direction: column;
            gap: 0px;
            align-items: center;
            width: 100vw;
            height: 100vh;
            top: 0;
            z-index: 30;
            left: 0;
            position: fixed;
            background-color: rgba(61, 59, 59, 0.61);
            box-shadow: 0px 0px 5px #000000;

        }

        .login-btn {
            padding: 10px 20px;
            background: #ce31dd;
            ;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin-left: 10px;
            text-decoration: none;
        }

    </style>
</head>

<body>
        <!--  HeaderBar section -->
        <div class="head-wrapper">
          <div class="head-box">
            <a href="./../client/home.php">
              <img class="brand-name" src=".\..\images\musicLogo.png" alt="brand-logo"></img>
            </a>
    
            <div class="head-tool">
              <ul class="head-list">
                <li><a class="head-list-link-item" href="./../client/search.html">Search</a> </li>
                <li><a class="head-list-link-item" href="./../client/Playlist.html">Playlist </a> </li>
                <li><a class="head-list-link-item" id="downloadLink" href="href='./../client/Download.php">Download</a></li>
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
                <p style="text-align:left;" >    <a href="./../client/Download.php" class="list-item-name">My Downloads</a></p>
                </a>
            </li>
            <li class="side-bar-list-item">
                <a class="side-bar-list-item-content" href="/">
                <p style="text-align:left;" >    <a href="./../client/PlayList.html
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
    <div class="container">
        <h2>My Downloads</h2>

        <?php if (empty($downloads)): ?>
            <p class="error-message">No songs downloaded yet.</p>
        <?php else: ?>
            <?php foreach ($downloads as $song): ?>
                <div class="download-card" id="song-<?= $song['download_id'] ?>" >
                <div style="display:flex; width:100%" onclick="playSong(<?= $song['song_id']?>)">
                <img class="song-image" src="./../images/musiccardImg1.webp" alt="Song Image">
                    <div class="song-details">
                        <h4><?= htmlspecialchars($song['title']) ?></h4>
                        <p>Artist: <?= htmlspecialchars($song['artist']) ?></p>
                        <p>Downloaded: <?= $song['downloaded_at'] ?></p>
                    </div>
            </div>
                    <div>
                    <button class="delete-btn" onclick="deleteDownload(<?= $song['download_id'] ?>)">Delete</button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="login-wrapper" style="display:none;">
                    <a href="./../userAuthentication/Login.php" class="login-btn">Login</a>
                   <!-- <div class="login-btn">Login       </div> -->


                    <br>
                    <p class="login-message">Please login to access this feature</p>
                </div>

                <div class="footnavbar-wrapper">
      <div class="footnavbar-box">
      <a href="./Home.php" class="nav-element">
      <span class="material-symbols-outlined element-icon">
            home
          </span>
          <p class="element-icon-name">Home</p>
        </a>
        <a href="./Search.html" class="nav-element">
          <span class="material-symbols-outlined element-icon">
            search </span>
          <p class="element-icon-name">Search</p>
        </a>
        <a href="#" class="nav-element" class="element-icon" id="search-btn">
          <img src="./../images/audioIcon.png" style=" scale:1.463 ;width:60px; height:40px; position:relative;top:-20px; " class="element-icon" alt="nav-icon"></img>
          <p class="element-icon-name" style=" position:relative ;top:-7px;" id="search-btn-msg">Search</p>
        </a>
        <a href="./playlist.html " class="nav-element">
          <span class="material-symbols-outlined element-icon active-foot-bar">
          Genres

          </span>
          <p class="element-icon-name">playlist</p>
        </a>
        <a href="./Download.php" class="nav-element">
          <span class="material-symbols-outlined element-icon">
            download
          </span>
          <p class="element-icon-name active-foot-bar">Download</p>
        </a>
      </div>
    </div>
    <script>
        function playSong(songId) {
            window.location.href = `SongPlay.php?id=${encodeURIComponent(songId)}`;
        }
        let username = localStorage.getItem("username");
        let userId = localStorage.getItem("userId");
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

        function deleteDownload(downloadId) {
            if (confirm("Are you sure you want to delete this song from downloads?")) {
                fetch("deleteDownload.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            downloadId
                        })
                    })
                    .then(response => response.text())
                    .then(data => {
                        if (data === "success") {
                            document.getElementById("song-" + downloadId).remove();
                        } else {
                            alert("Error deleting song.");
                        }
                    })
                    .catch(error => console.error("Error:", error));
            }
        }
           // Sidebar Profile Handling
        let profileBtn = document.querySelector(".profile");
        let sideBarContainer = document.querySelector(".side-bar");
        let fadeBackGroundContainer = document.querySelector(".background-wrapper");
        let closeSidebarBtn = document.querySelector(".cancel-btn");
    
        if (profileBtn) {
            profileBtn.addEventListener("pointerdown", () => {
                if (sideBarContainer && fadeBackGroundContainer) {
                    sideBarContainer.style.right = "0";
                    fadeBackGroundContainer.style.display = "flex";
                }
            });
        }
        let isLoggedIn = !!username; // Convert to boolean

        
        if (isLoggedIn) {
            // If user is logged in, show profile details
            let dob = localStorage.getItem("dob") || "N/A";
            let city = localStorage.getItem("city") || "Unknown";
    
            document.querySelector(".profile-user-name").innerText = username;
            document.querySelector(".basic-detail").innerText = `DOB: ${dob}, City: ${city}`;
            
            // Show Logout button
            let authBtn = document.getElementById("authBtn");
            if (authBtn) {
                authBtn.innerText = "Logout";
                authBtn.style.cursor = "pointer";
    
                authBtn.addEventListener("click", function() {
                    localStorage.clear(); // Clears all stored data
                    window.location.href = "./../client/Home.php"; // Redirect to Home
                });
            }
        } else {
            // If user is not logged in, show Login button
            let profileName = document.getElementById("profileName");
            let profileDetails = document.getElementById("profileDetails");
            let authBtn = document.getElementById("authBtn");
    
            if (profileName) profileName.innerText = "Guest User";
            if (profileDetails) profileDetails.innerText = "Please log in.";
    
            if (authBtn) {
                authBtn.innerText = "Login";
                authBtn.style.cursor = "pointer";
    
                authBtn.addEventListener("click", function() {
                    window.location.href = "./../userAuthentication/Login.php"; // Redirect to Login
                });
            }
        }
        
        // Sidebar Close Events
        if (closeSidebarBtn) {
            closeSidebarBtn.addEventListener("pointerdown", () => {
                sideBarContainer.style.right = "-300px";
                fadeBackGroundContainer.style.display = "none";
            });
        }
        
        if (fadeBackGroundContainer) {
            fadeBackGroundContainer.addEventListener("pointerdown", () => {
                sideBarContainer.style.right = "-300px";
                fadeBackGroundContainer.style.display = "none";
            });
        }

               
        let loginWrapper = document.querySelector('.login-wrapper');
        isloggedIn=localStorage.getItem('userId')
        if (!isLoggedIn && loginWrapper) {
            loginWrapper.style.display = "flex";
        }

        
    </script>

</body>

</html>