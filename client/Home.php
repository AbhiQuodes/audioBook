<?php
include "./../userAuthentication/config.php";
// Fetch data from musicsongs table
$sql = "SELECT id, title, category, duration FROM musicsongs";
$result = $conn->query($sql);

// Store fetched songs in $songDetails array
$songDetails = [];

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $songDetails[] = [
      "id" => $row["id"],
      "name" => $row["title"],
      "category" => $row["category"],
      "duration" => $row["duration"],
    ];
  }
}

// Close connection
$conn->close();

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

// require './../PHPMailer/Exception.php';
// require './../PHPMailer/PHPMailer.php';
// require './../PHPMailer/SMTP.php';

// $mail = new PHPMailer(true);

// try {
//   $mail->isSMTP();
//   $mail->Host = 'smtp.gmail.com';
//   $mail->SMTPAuth = true;
//   $mail->Username = 'your-email@gmail.com'; // Your Gmail
//   $mail->Password = 'your-email-password'; // Use App Password for security
//   $mail->SMTPSecure = 'tls';
//   $mail->Port = 587;

//   $mail->setFrom('your-email@gmail.com', 'Your Name');
//   $mail->addAddress('recipient@example.com');

//   $mail->isHTML(true);
//   $mail->Subject = 'Contact Form Submission';
//   $mail->Body    = 'Hello, this is a test email from your contact form.';

//   $mail->send();
//   echo 'Message sent successfully!';
// } catch (Exception $e) {
//   echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
// }



?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="Home.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <style>
    .material-symbols-outlined {
      color: rgb(0, 0, 0);
      font-variation-settings:
        'FILL' 0,
        'wght' 250,
        'GRAD' 0,
        'opsz' 24
    }
  </style>
  <title>Music Song</title>
</head>

<body>

  <div class="top-container">
    <!--  HeaderBar section -->
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
    <!-- SearchBar seaction -->
    <div class="search-wrapper">
      <div class="search-box" onclick="window.location.href='search.html'">
        <span class="material-symbols-outlined" style="color:#fff;">
          search
        </span>
        <form action="search.php" method="GET" style="width: 80%;">
          <input type="text" class="input-search" name="query" placeholder="Search music...">
          <!-- <?php
                if (isset($_GET['query'])) {
                  echo "<script>window.location.href='search.php?query=" . urlencode($_GET['query']) . "'</script>";
                }
                ?> -->
        </form>

      </div>
    </div>
  </div>
  <!-- Intro-Section -->
  <section class="introductory-container">
    <div class="introductory-text-box">
      <div class="introductory-para">
        <div class="introductory-text">
          <div class="introductory-text-one">
            Feel the rhythm, embrace the melody.
            <h3 class="introductory-text-two"> Let the music take you on a journey! </h3>
            let's start music !
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- song-list -->
  <div class="music-container">
    <div class="category-wrapper">
      <div class="category-box">
        <h2 class="category-name">Trending Songs</h2>

        <button class="view-btn">
          View All
        </button>
      </div>
    </div>
    <ul class="music-box-list" id="residential-container">
      <?php foreach ($songDetails as $song) {
        if ($song["category"] === "Trending" || $song["category"] === "Classical" || $song["category"] === "Pop") {
          echo "<li class='music-box-list-item' key='{$song['id']}'>";
          echo "<a href='./songplay.php?id=" . $song['id'] . "'>";
          echo "<span class='favorite-play-heart-icon material-symbols-outlined' style='color: #bda9a9;'>favorite</span>";
          echo "<div class='img-box'>";
          echo "<img src='./../images/musicCardImg1.webp' class='music-image' alt='music-img'></img>";
          echo "</div>";
          echo "<div class='text'>{$song['name']}</div>";
          echo "</a>";
          echo "</li>";
        }
      }; ?>
    </ul>
    <div class="category-wrapper">
      <div class="category-box">
        <h2 class="category-name">Old Songs</h2>
        <button class="view-btn">
          View All
        </button>
      </div>
    </div>
    <ul class="music-box-list">
      <?php foreach ($songDetails as $song) {
        if ($song["category"] === "Romantic") {
          echo "<li class='music-box-list-item' key='{$song['id']}'>";
          echo "<a href='./songplay.php?id=" . $song['id'] . "'>";
          echo "<span class='favorite-play-heart-icon material-symbols-outlined' style='color: #bda9a9;'>favorite</span>";
          echo "<div class='img-box'>";
          echo "<img src='./../images/musicCardImg1.webp' class='music-image' alt='music-img'></img>";
          echo "</div>";
          echo "<div class='text'>{$song['name']}</div>";
          echo "</a>";
          echo "</li>";
        }
      }; ?>
    </ul>



    <div class="category-wrapper">
      <div class="category-box">
        <h2 class="category-name">English Songs</h2>

        <button class="view-btn">
          View All
        </button>
      </div>
    </div>
    <ul class="music-box-list" id="rental-container">
      <?php foreach ($songDetails as $song) {
        if ($song["category"] === "Other") {
          echo "<li class='music-box-list-item' key='{$song['id']}'>";
          echo "<a href='./songplay.php?id=" . $song['id'] . "'>";
          echo "<span class='favorite-play-heart-icon material-symbols-outlined' style='color: #bda9a9;'>favorite</span>";
          echo "<div class='img-box'>";
          echo "<img src='./../images/musicCardImg1.webp' class='music-image' alt='music-img'></img>";
          echo "</div>";
          echo "<div class='text'>{$song['name']}</div>";
          echo "</a>";
          echo "</li>";
        }
      }; ?>

    </ul>
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



  <!-- Contact Section Wrapper -->
  <div class="contact-wrapper">
    <div class="contact-container">
      <!-- Left Side: Quick Support Form -->
      <div class="contact-form">
        <p>GET IN TOUCH WITH US.</p>
        <h2>Quick Support</h2>

        <form>
        <input type="text" placeholder="Your Name" required />
          <input type="tel" placeholder="Your Phone" required />
          <input type="text" placeholder="Subject" required />
          <textarea placeholder="Write Message" rows="5" required></textarea>
          <button type="submit">SEND A MESSAGE</button>
        </form>
      </div>

      <!-- Right Side: Contact Info -->

      <div class="contact-info">
        <h2>CONTACT INFO</h2>
        <p>NASHIK, MAHARASHTRA</p>

        <ul>
          <li class="contact-list-item"><strong>Phone:</strong> +0085 458 3695</li>
          <li class="contact-list-item"><strong>FAX:</strong> +0045 4853648</li>
          <li class="contact-list-item"><strong>EMAIL:</strong> audiobook_sana@gmail.com</li>
          <li class="contact-list-item"><strong>Open:</strong> SUNDAY - FRIDAY 08:00 - 18:00</li>
        </ul>

        <!-- Social Media Icons -->
        <div class="social-icons">
          <a href="#"><i class="fab fa-facebook"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
        </div>
      </div>
    </div>
  </div>
  <!-- foot-navigation-bar-->
  <div class="footnavbar-wrapper">
    <div class="footnavbar-box">
    <a href="./Home.php" class="nav-element">
    <span class="material-symbols-outlined element-icon active-foot-bar">
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
      <a href="./playlist.html" class="nav-element">
        <span class="material-symbols-outlined element-icon">
        Genres

        </span>
        <p class="element-icon-name">playlist</p>
      </a>
      <a href="./Download.php" class="nav-element">
        <span class="material-symbols-outlined element-icon">
          download
        </span>
        <p class="element-icon-name">Download</p>
      </a>
    </div>
  </div>


  <script>
    let profileBtn = document.querySelector(".profile");
    let sideBarContainer = document.querySelector(".side-bar");
    let fadeBackGroundContainer = document.querySelector(".background-wrapper");
    let closeSidebarBtn = document.querySelector(".cancel-btn");
    let searchStartBtn = document.querySelector("#search-btn");
    let searchBtntext = document.querySelector("#search-btn-msg");
    let inputField = document.querySelector(".input-search");
    let username = localStorage.getItem("username");
    let dob = localStorage.getItem("dob") || "N/A";
    let city = localStorage.getItem("city") || "Unknown";

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

    // Fetch username from localStorage
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



    // Check if browser supports SpeechRecognition
    const SpeechRecognition =
      window.SpeechRecognition || window.webkitSpeechRecognition;
    if (!SpeechRecognition) {
      alert("Speech Recognition not supported in your browser.");
    } else {
      const recognition = new SpeechRecognition();
      recognition.continuous = true; // Keep listening
      recognition.interimResults = true; // Show partial results
      let isListening = false;

      searchStartBtn.addEventListener("pointerdown", () => {
        if (!isListening) {
          recognition.start();
          searchBtntext.textContent = "Listening";
        }

        isListening = !isListening;
      });

      searchStartBtn.addEventListener("pointerup", () => {
        recognition.stop();
        searchBtntext.textContent = "Search";

        isListening = !isListening;
        if (inputField.value != "") {
          let searchValue = inputField.value;

          setTimeout(() => {
            //sending the search value in the query parameter;
            window.location.href = `http://localhost/music/client/search.php?query=${searchValue}`;
          }, 1000); // Redirects after 3 seconds
        }
      });

      recognition.onresult = (event) => {
        let transcript = "";
        for (let i = event.resultIndex; i < event.results.length; i++) {
          transcript += event.results[i][0].transcript;
        }
        inputField.value += transcript;
      };

      recognition.onerror = (event) => {
        console.error("Speech recognition error:", event.error);
      };
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

    const sendResponseEmail = (e) => {
    if (email && message) {
      e.preventDefault();
      emailjs
        .sendForm(
          "service_lgvir2j",
          "template_pd6dqar",
          e.target,
          "cLdebBPtnShamwbpz"
        )
        .then(
          (result) => {},
          (error) => {
            alertt("Some Error Occured" + error.text);
          }
        );
    }
  };

  document.querySelectorAll(".favorite-play-heart-icon").forEach((icon) => {
    icon.addEventListener("click", async function (event) {
      event.preventDefault();
      
      const userId = localStorage.getItem('userId'); // Replace with actual user ID from session or database
      const songId = this.closest("a").href.split("id=")[1];

      // Fetch user's playlists
      const response = await  fetch(`playlist.php?operation=viewplaylist&user_id=${userId}`)
      const playlists = await response.json();

      if (playlists.length === 0) {
        alert("No playlists found. Create a playlist first.");
        return;
      }
      // Create a dropdown list
      let dropdown = document.createElement("div");
      dropdown.classList.add("playlist-dropdown");
      dropdown.style.position = "absolute";
      dropdown.style.background = "#fff";
      dropdown.style.border = "1px solid #ccc";
      dropdown.style.padding = "5px";
      dropdown.style.cursor = "pointer";
         // Apply dynamic styles
         Object.assign(dropdown.style, {
        position: "absolute",
        textAlign: "center",
        marginTop: "10px",
        borderRadius: "10px",
        backdropFilter: "blur(8px)",
        boxShadow: "0px 0px 6px #0c0505",
        backgroundColor: "#a6909078",
        padding: "10px",
        cursor: "pointer",
        zIndex: "1000"
      });
      result=playlists.data;
      if(result.length == 0)
      {
        let item = document.createElement("div");
        item.innerHTML = "<a style=`color:black;` href='./Playlist.html'>create playlist</a>";
        dropdown.appendChild(item);

      }
      Array.from(playlists.data).forEach((playlist) => {
        let item = document.createElement("div");
        item.textContent = playlist.name;
        item.dataset.playlistId = playlist.id;
        item.addEventListener("click", async function () {
          const playlistId = this.dataset.playlistId;
          const addResponse = await fetch(
            `Playlist.php?operation=AddSong&user_id=${userId}&playlist_id=${playlistId}&song_id=${songId}`
          );
          const result = await addResponse.json();
           if (result.success==true) {
            alert("Song added to playlist!");
          } else {
            alert("Failed to add whysong.");
          }

          dropdown.remove();
        });
        dropdown.appendChild(item);
      });

      document.body.appendChild(dropdown);
      let rect = this.getBoundingClientRect();
      dropdown.style.left = `${rect.left}px`;
      dropdown.style.top = `${rect.bottom + window.scrollY}px`;
    });
  });
  
  </script>




</body>

</html>