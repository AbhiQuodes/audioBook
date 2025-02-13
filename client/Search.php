<?php
session_start();
$conn = new mysqli("localhost", "root", "", "userDB");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
$loggedIn = isset($_SESSION["username"]);

// Get search query

$searchQuery = isset($_GET["query"]) ? trim($_GET["query"]) : "";


$searchQuery = isset($_GET["query"]) ? trim($_GET["query"]) : "";
$category = isset($_GET["category"]) ? trim($_GET["category"]) : "All";

$results = [];
if ($searchQuery != "") {
    try {
        // Base Query

        $sql = "SELECT * FROM musicSongs WHERE title LIKE '%$searchQuery%'";

        // Apply category filter (except for "All")
        if ($category !== "All") {
            $sql .= " AND category = '$category'";
        }

        $result = $conn->query($sql);


        if (!$result) {
            throw new Exception("Error: " . $conn->error);
        }

        if ($result != "No results found" && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='song-card' data-category='" . htmlspecialchars($row["category"]) . "'>";
                echo "<h3>" . htmlspecialchars($row["title"]) . "</h3>";
                echo "<p>Category: " . htmlspecialchars($row["category"]) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p class='no-results'>No results found</p>";
        }
    } catch (Exception $e) {
        echo "<p class='no-results'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <link rel="stylesheet" href="Home.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
        * {
            padding: 0;
            margin: 0;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0px;
            padding: 0;

        }

        .material-symbols-outlined {
            color: rgb(0, 0, 0);
            font-variation-settings:
                'FILL' 0,
                'wght' 250,
                'GRAD' 0,
                'opsz' 24
        }



        .search-wrapper {
            display: flex;
            width: 100vw;
            justify-content: center;
            align-items: center;
            position: relative;
            flex-direction: column;
            gap: 30px;
            margin-top: 25px;
            top: 30px;
        }

        .search-box {
            display: flex;
            justify-content: space-evenly;
            width: 90%;
            height: 40px;
            padding: 5px 0px;
            max-width: 650px;
            background: transparent;
            box-shadow: 0px 4px 10px 0px rgba(231, 225, 225, 0.06);
            border-radius: 20px;
            border: 1px solid grey;
            align-items: center;
            min-width: 305px;
        }

        .search-icon {
            position: relative;
            width: 18px;
            height: 18px;
        }

        .input-search {
            color: rgb(224, 215, 215);
            font-family: Roboto;
            font-size: 16px;
            font-weight: 400;
            position: relative;
            left: -20px;
            line-height: 16.41px;
            text-align: left;
            text-underline-position: from-font;
            text-decoration-skip-ink: none;
            opacity: 1;
            background: transparent;
            outline: none;
            border: none;
            width: 95%;
        }

        .category-box::-webkit-scrollbar {
            height: 0px;
            /* Set height to zero */
        }

        .category-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .category-box {
            display: flex;
            overflow-x: auto;
            white-space: nowrap;
            gap: 10px;
            margin-bottom: 20px;
            width: 90vw;
            max-width: 650px;
            padding-bottom: 10px;
        }

        .category-btn {
            padding: 8px 16px;
            background: #ddd;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .category-btn:hover {
            background: #bbb;
        }

        /* Login Button */
        .login-btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #333;
            /* Dark gray text */
            background-color: transparent;
            /* No background */
            border: 1px solid #ccc;
            /* Light gray border */
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

        .songs-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .song-card {
            width: 200px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
        }

        .no-results {
            font-size: 18px;
            color: red;
        }

        @media (max-width:600px) {
            .input-search {
                left: -8px;
            }

            .search-wrapper {
                top: 0px;

            }
        }
    </style>
</head>

<body>
    <div class="head-wrapper">
        <div class="head-box">
            <a href="/">
                <img class="brand-name" src=".\..\images\musicLogo.png" alt="brand-logo"></img>
            </a>

            <div class="head-tool">
                <ul class="head-list">
                    <li><a class="head-list-link-item" href="#">Search</a> </li>
                    <li><a class="head-list-link-item" href="#">Playlist </a> </li>
                    <li><a class="head-list-link-item" href="#">Download </a> </li>
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
        <img class="cancel-btn" src=".\..\images\closeIcon.svg" alt="cancel-btn"></img>
        <div class="profile-box">
            <div class="profile">

                <img
                    src=".\..\images\carbon_user.svg"
                    class="profile-user-icon"
                    alt="profile-icon"></img>
            </div>
            <div class="profile-user-details">
                <p class="profile-user-name">Abhinav Kumar</p>
                <p class="basic-detail">Male , DOB 1 Jan 2008</p>
            </div>
            <!-- <img src={editIcon} class="edit-icon" alt="edit-icon"></img> -->
        </div>
        <ul class="side-bar-list">
            <li class="side-bar-list-item">
                <a class="side-bar-list-item-content" href="/">
                    <!-- <img
                  src={orderIcon}
                  class="list-item-icon"
                  alt="list-icon"
                ></img> -->
                    <p class="list-item-name">My Orders</p>
                </a>
            </li>
            <li class="side-bar-list-item">
                <a class="side-bar-list-item-content" href="/">
                    <!-- <img
                  src={playIcon}
                  class="list-item-icon"
                  alt="list-icon"
                ></img> -->
                    <p class="list-item-name">playlist</p>
                </a>
            </li>

            <li class="side-bar-list-item">
                <a class="side-bar-list-item-content" href="/">
                    <!-- <img
                  src={passwordIcon}
                  class="list-item-icon"
                  alt="list-icon"
                ></img> -->
                    <p class="list-item-name">Change Password</p>
                </a>
            </li>

            <li class="side-bar-list-item">
                <a class="side-bar-list-item-content" href="/">
                    <!-- <img
                  src={deliveryIcon}
                  class="list-item-icon"
                  alt="list-icon"
                ></img> -->
                    <p class="list-item-name">Delivery Addresses</p>
                </a>
            </li>
        </ul>

        <a href="/" class="about-box">
            <!-- <img src={infoIcon} class="list-item-icon" alt="list-icon"></img> -->
            <p class="list-item-name">
                About music song
                <!-- <p class="basic-detail" style={{ fontSize "10px" }}> -->
                <p class="basic-detail">
                    Version 1.0.1
                </p>
            </p>
        </a>
        <div class="log-out-box">
            <!-- <img
              src={logOutIcon}
              class="list-item-icon"
              alt="list-icon"
            ></img> -->
            <p class="list-item-name">Logout</p>
        </div>
    </div>
    <div class="search-wrapper">
        <div class="search-box">
            <span class="material-symbols-outlined" style="color:rgb(165, 155, 155);">
                search
            </span>
            <form action="search.php" method="GET" style="width: 80%;">
                <input type="text" class="input-search" id="searchInput" value="<?= htmlspecialchars($searchQuery) ?>" placeholder="Search music...">
                <div class="login-wrapper" style="display:none;">
                    <a href="./../userAuthentication/Login.php" class="login-btn">Login</a>
                    <br>
                    <p class="login-message">Please login to access this feature</p>
                </div>
            </form>

        </div>
        <!-- Horizontal Scroll Categories -->
        <div class="category-container">
            <div class="category-box">
                <button class="category-btn active" data-category="All">All</button>
                <button class="category-btn" data-category="Trending">Trending</button>
                <button class="category-btn" data-category="Romantic">Romantic</button>
                <button class="category-btn" data-category="Pop">Pop</button>
                <button class="category-btn" data-category="Rock">Rock</button>
                <button class="category-btn" data-category="Hip-Hop">Hip-Hop</button>
                <button class="category-btn" data-category="Jazz">Jazz</button>
                <button class="category-btn" data-category="Classical">Classical</button>
            </div>
        </div>

    </div>
    <!-- 
    <div class="search-container">
        <input type="text" class="search-input" id="searchInput" value="<?= htmlspecialchars($searchQuery) ?>" placeholder="Search music...">
        <?php if (!$loggedIn): ?>
            <a href="./../userAuthentication/Login.php" class="login-btn">Login</a>
        <?php endif; ?>
    </div> -->

    <!-- Horizontal Scroll Categories -->
    <!-- <div class="category-container">
        <button class="category-btn active" data-category="All">All</button>
        <button class="category-btn" data-category="Trending">Trending</button>
        <button class="category-btn" data-category="Romantic">Romantic</button>
        <button class="category-btn" data-category="Pop">Pop</button>
        <button class="category-btn" data-category="Rock">Rock</button>
        <button class="category-btn" data-category="Hip-Hop">Hip-Hop</button>
        <button class="category-btn" data-category="Jazz">Jazz</button>
        <button class="category-btn" data-category="Classical">Classical</button>
    </div> -->

    <!-- Songs Display -->
    <!-- <div class="songs-container" id="songsContainer">
        <?php if (count($results) > 0): ?>
            <?php foreach ($results as $song): ?>
                <div class="song-card" data-category="<?= htmlspecialchars($song["category"]) ?>">
                    <h3><?= htmlspecialchars($song["title"]) ?></h3>
                    <p>Category: <?= htmlspecialchars($song["category"]) ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-results">No results found</p>
        <?php endif; ?>
    </div> -->

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Check if the user is logged in (exists in localStorage)
            let isLoggedIn = localStorage.getItem("username") ? true : false;
            let loginWrapper = document.querySelector('.login-wrapper');
            if (!isLoggedIn) {
                loginWrapper.style.display = "flex";
            }
            let searchInput = document.getElementById("searchInput");
            let categoryButtons = document.querySelectorAll(".category-btn");
            let songsContainer = document.getElementById("songsContainer");

            function fetchResults() {
                if (searchInput.value != '') {
                    let query = searchInput.value.trim();
                    let category = document.querySelector(".category-btn.active")?.dataset.category || "All";

                    // Fetch filtered results from the server
                    fetch(`search.php?query=${encodeURIComponent(query)}&category=${encodeURIComponent(category)}`)
                        .then(response => response.text())
                        .then(data => {
                            songsContainer.innerHTML = data;
                        })
                        .catch(error => console.error("Error fetching results:", error));
                }

            }
            // Event listener for search input
            searchInput.addEventListener("input", fetchResults);

            // Event listeners for category buttons
            categoryButtons.forEach(button => {
                button.addEventListener("click", function() {
                    categoryButtons.forEach(btn => btn.classList.remove("active")); // Remove active class from all
                    this.classList.add("active"); // Set active class to clicked button
                    fetchResults(); // Fetch results based on new category
                });
            });

            fetchResults(); // Load initial results
        });
    </script>
    <script>
        let profileBtn = document.querySelector(".profile");
        let sideBarContainer = document.querySelector(".side-bar");
        let fadeBackGroundContainer = document.querySelector(".background-wrapper");
        let closeSidebarBtn = document.querySelector(".cancel-btn");
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