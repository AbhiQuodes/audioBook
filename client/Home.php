<?php

$songDetails = [
  [
    "id" => 1,
    "name" => "Blinding Lights",
    "category" => "Trending",
    "duration" => "3:22",
    "genre" => "Synthwave, Pop"
  ],
  [
    "id" => 2,
    "name" => "Shape of You",
    "category" => "Trending",
    "duration" => "3:54",
    "genre" => "Pop"
  ],
  [
    "id" => 3,
    "name" => "Bohemian Rhapsody",
    "category" => "Old 90's Song",
    "duration" => "5:55",
    "genre" => "Rock"
  ],
  [
    "id" => 4,
    "name" => "Believer",
    "category" => "Trending",
    "duration" => "3:24",
    "genre" => "Rock, Pop"
  ],
  [
    "id" => 5,
    "name" => "Hotel California",
    "category" => "Old 90's Song",
    "duration" => "6:31",
    "genre" => "Rock"
  ],
  [
    "id" => 6,
    "name" => "Someone Like You",
    "category" => "English Song",
    "duration" => "4:45",
    "genre" => "Pop, Soul"
  ],
  [
    "id" => 7,
    "name" => "Perfect",
    "category" => "Trending",
    "duration" => "4:23",
    "genre" => "Pop"
  ],
  [
    "id" => 8,
    "name" => "Billie Jean",
    "category" => "Old 90's Song",
    "duration" => "4:54",
    "genre" => "Pop"
  ],
  [
    "id" => 9,
    "name" => "Let Her Go",
    "category" => "English Song",
    "duration" => "4:12",
    "genre" => "Folk, Pop"
  ],
  [
    "id" => 10,
    "name" => "Rolling in the Deep",
    "category" => "English Song",
    "duration" => "3:49",
    "genre" => "Pop, Soul"
  ],
  [
    "id" => 11,
    "name" => "Sweet Child O' Mine",
    "category" => "Old 90's Song",
    "duration" => "5:56",
    "genre" => "Rock"
  ],
  [
    "id" => 12,
    "name" => "Closer",
    "category" => "Trending",
    "duration" => "4:04",
    "genre" => "EDM, Pop"
  ],
  [
    "id" => 13,
    "name" => "Take Me to Church",
    "category" => "English Song",
    "duration" => "4:01",
    "genre" => "Indie Rock"
  ],
  [
    "id" => 14,
    "name" => "Uptown Funk",
    "category" => "Trending",
    "duration" => "4:30",
    "genre" => "Funk, Pop"
  ],
  [
    "id" => 15,
    "name" => "In the End",
    "category" => "Old 90's Song",
    "duration" => "3:36",
    "genre" => "Rock, Rap"
  ],
  [
    "id" => 16,
    "name" => "Memories",
    "category" => "Trending",
    "duration" => "3:15",
    "genre" => "Pop"
  ],
  [
    "id" => 17,
    "name" => "Radioactive",
    "category" => "Trending",
    "duration" => "3:06",
    "genre" => "Alternative Rock"
  ],
  [
    "id" => 18,
    "name" => "Yesterday",
    "category" => "Old 90's Song",
    "duration" => "2:05",
    "genre" => "Rock"
  ],
  [
    "id" => 19,
    "name" => "Counting Stars",
    "category" => "English Song",
    "duration" => "4:17",
    "genre" => "Pop Rock"
  ],
  [
    "id" => 20,
    "name" => "Shape of My Heart",
    "category" => "Old 90's Song",
    "duration" => "4:39",
    "genre" => "Pop"
  ]
];


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

    <!-- SearchBar seaction -->
    <div class="search-wrapper">
      <div class="search-box">
        <span class="material-symbols-outlined" style="color:#fff;">
          search
        </span>
        <form action="search.php" method="GET" style="width: 80%;">
          <input type="text" class="input-search" name="query" placeholder="Search music..." onclick="window.location.href='search.php'">
          <?php
          if (isset($_GET['query'])) {
            echo "<script>window.location.href='search.php?query=" . urlencode($_GET['query']) . "'</script>";
          }
          ?>
        </form>

      </div>
    </div>
  </div>
  <!-- Intro-Section -->
  <section class="introductory-container">
    <div class="introductory-text-box">
      <div class="introductory-para">
        <!-- <div class="para-img-box">
          <img
            class="para-img"
            src='./../images/introImage.png'
            alt="intro-image"></img>
        </div> -->
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

  <div class="property-container">
    <div class="category-wrapper">
      <div class="category-box">
        <h2 class="category-name">Trending Songs</h2>

        <button class="view-btn">
          View All
        </button>
      </div>
    </div>
    <ul class="page-product-box-list" id="residential-container">
      <?php foreach ($songDetails as $song) {
        if ($song["category"] === "Trending") {
          echo "<li class='product-contact-box-list-item' key='{$song['id']}'>";
          echo "<a href='./songplay.php?id=" . $song['id'] . "'>";
          echo "<span class='favorite-play-heart-icon material-symbols-outlined' style='color: #bda9a9;'>favorite</span>";
          echo "<div class='img-box'>";
          echo "<img src='./../images/musicCardImg1.webp' class='page-product-image' alt='product-img'></img>";
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
    <ul class="page-product-box-list">
      <?php foreach ($songDetails as $song) {
        if ($song["category"] === "Old 90's Song") {
          echo "<li class='product-contact-box-list-item' key='{$song['id']}'>";
          echo "<a href='./songplay.php?id=" . $song['id'] . "'>";
          echo "<span class='favorite-play-heart-icon material-symbols-outlined' style='color: #bda9a9;'>favorite</span>";
          echo "<div class='img-box'>";
          echo "<img src='./../images/musicCardImg1.webp' class='page-product-image' alt='product-img'></img>";
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
    <ul class="page-product-box-list" id="rental-container">
      <?php foreach ($songDetails as $song) {
        if ($song["category"] === "English Song") {
          echo "<li class='product-contact-box-list-item' key='{$song['id']}'>";
          echo "<a href='./songplay.php?id=" . $song['id'] . "'>";
          echo "<span class='favorite-play-heart-icon material-symbols-outlined' style='color: #bda9a9;'>favorite</span>";
          echo "<div class='img-box'>";
          echo "<img src='./../images/musicCardImg1.webp' class='page-product-image' alt='product-img'></img>";
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
      <a href="#" class="nav-element">
        <span class="material-symbols-outlined element-icon">
          home
        </span>
        <p class="element-icon-name">Home</p>
      </a>
      <a href="#" class="nav-element">
        <span class="material-symbols-outlined element-icon">
          search </span>
        <p class="element-icon-name">Search</p>
      </a>
      <a href="#" class="nav-element" class="element-icon" id="search-btn">
        <img src="./../images/audioIcon.png" style=" scale:1.463 ;width:60px; height:40px; position:relative;top:-20px; " class="element-icon" alt="nav-icon"></img>
        <p class="element-icon-name" style=" position:relative ;top:-7px;" id="search-btn-msg">Search</p>
      </a>
      <a href="#" class="nav-element">
        <span class="material-symbols-outlined element-icon">
          genres
        </span>
        <p class="element-icon-name">playlist</p>
      </a>
      <a href="#" class="nav-element">
        <span class="material-symbols-outlined element-icon">
          download
        </span>
        <p class="element-icon-name">Download</p>
      </a>
    </div>
  </div>









  <script src="Home.js"></script>
</body>

</html>