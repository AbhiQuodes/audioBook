// let profileBtn = document.querySelector(".profile");
// let sideBarContainer = document.querySelector(".side-bar");
// let fadeBackGroundContainer = document.querySelector(".background-wrapper");
// let closeSidebarBtn = document.querySelector(".cancel-btn");
// let searchStartBtn = document.querySelector("#search-btn");
// let searchBtntext = document.querySelector("#search-btn-msg");
// let inputField = document.querySelector(".input-search");



// let username = localStorage.getItem("username");
// let dob = localStorage.getItem("dob") || "N/A";
// let city = localStorage.getItem("city") || "Unknown";


// alert(username)

//         // Fetch username from localStorage

//         if (username) {

//             // If user is logged in, show profile details
//             let dob = localStorage.getItem("dob") || "N/A";
//             let city = localStorage.getItem("city") || "Unknown";

//             document.getElementById("profileName").innerText = username;
//             document.getElementById("profileDetails").innerText = `DOB: ${dob}, City: ${city}`;

//             // Show Logout button
//             let authBtn = document.getElementById("authBtn");
//             authBtn.innerText = "Logout";
//             authBtn.style.cursor = "pointer";

//             authBtn.addEventListener("click", function () {
//                 localStorage.removeItem("username"); // Erase only username
//                 window.location.href = "./../client/Home.php"; // Redirect to Home
//             });
//         } else {
//             // If user is not logged in, show Login button
//             document.getElementById("profileName").innerText = "Guest User";
//             document.getElementById("profileDetails").innerText = "Please log in.";

//             let authBtn = document.getElementById("authBtn");
//             authBtn.innerText = "Login";
//             authBtn.style.cursor = "pointer";

//             authBtn.addEventListener("click", function () {
//                 window.location.href = "./../userAuthentication/Login.php"; // Redirect to Login
//             });
//         }



// // Check if browser supports SpeechRecognition
// const SpeechRecognition =
//   window.SpeechRecognition || window.webkitSpeechRecognition;
// if (!SpeechRecognition) {
//   alert("Speech Recognition not supported in your browser.");
// } else {
//   const recognition = new SpeechRecognition();
//   recognition.continuous = true; // Keep listening
//   recognition.interimResults = true; // Show partial results
//   let isListening = false;

//   searchStartBtn.addEventListener("pointerdown", () => {
//     if (!isListening) {
//       recognition.start();
//       searchBtntext.textContent = "Listening";
//     }

//     isListening = !isListening;
//   });

//   searchStartBtn.addEventListener("pointerup", () => {
//     recognition.stop();
//     searchBtntext.textContent = "Search";

//     isListening = !isListening;
//     if (inputField.value != "") {
//       let searchValue = inputField.value;

//       setTimeout(() => {
//         //sending the search value in the query parameter;
//         window.location.href = `http://localhost/music/client/search.php?query=${searchValue}`;
//       }, 1000); // Redirects after 3 seconds
//     }
//   });

//   recognition.onresult = (event) => {
//     let transcript = "";
//     for (let i = event.resultIndex; i < event.results.length; i++) {
//       transcript += event.results[i][0].transcript;
//     }
//     inputField.value += transcript;
//   };

//   recognition.onerror = (event) => {
//     console.error("Speech recognition error:", event.error);
//   };
// }

// profileBtn.addEventListener("pointerdown", () => {
//   if (sideBarContainer.style.right != "0") {
//     sideBarContainer.style.right = 0;
//     fadeBackGroundContainer.style.display = "flex";
//   }
// });
// closeSidebarBtn.addEventListener("pointerdown", () => {
//   sideBarContainer.style.right = "-300px";
//   fadeBackGroundContainer.style.display = "none";
// });
// fadeBackGroundContainer.addEventListener("pointerdown", () => {
//   sideBarContainer.style.right = "-300px";
//   fadeBackGroundContainer.style.display = "none";
// });