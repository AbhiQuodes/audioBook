<?php
include './../userAuthentication/config.php'; // Include database connection
header('Content-Type: application/json'); // Set response type
ob_clean(); // Clear any unexpected output

// 1️⃣ Create playlist table if not exists
$sql_create_playlist = "CREATE TABLE IF NOT EXISTS playlist(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) UNIQUE NOT NULL
)";
$conn->query($sql_create_playlist);

// 2️⃣ Create user_playlists table if not exists
$sql_create_user_playlist = "CREATE TABLE IF NOT EXISTS user_playlists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    playlist_id INT NOT NULL,
    user_id INT NOT NULL,
    UNIQUE (playlist_id, user_id),
    FOREIGN KEY (playlist_id) REFERENCES playlist(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
$conn->query($sql_create_user_playlist);

// 3️⃣ Create song_playlists table if not exists
$sql_create_song_playlists = "CREATE TABLE IF NOT EXISTS song_playlists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_playlist_id INT NOT NULL,
    song_id INT NOT NULL,
    user_id INT NOT NULL,
    UNIQUE (user_playlist_id, song_id),
    FOREIGN KEY (user_playlist_id) REFERENCES user_playlists(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (song_id) REFERENCES musicsongs(id) ON DELETE CASCADE
)";
$conn->query($sql_create_song_playlists);

$operation = isset($_GET['operation']) ? $_GET['operation'] : '';
// $user_id = $_GET['user_id'];

if ($operation === 'Addplaylist') {
    $playlist_name = isset($_GET['playlist_name']) ? trim($_GET['playlist_name']) : null;
    $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;

    // ✅ Validate inputs
    if (empty($playlist_name) || empty($user_id)) {
        echo json_encode(["success" => false, "error" => "Missing playlist name or user ID"]);
        exit();
    }

    // 1️⃣ Check if playlist already exists
    $stmt = $conn->prepare("SELECT id FROM playlist WHERE name = ?");
    $stmt->bind_param("s", $playlist_name);
    $stmt->execute();
    $stmt->bind_result($playlist_id);
    $stmt->fetch();
    $stmt->close();

    if (!$playlist_id) { // If playlist doesn't exist, create it
        $stmt = $conn->prepare("INSERT INTO playlist (name) VALUES (?)");
        $stmt->bind_param("s", $playlist_name);
        if ($stmt->execute()) {
            $playlist_id = $stmt->insert_id;
        }
        $stmt->close();
    }

    if (!$playlist_id) {
        echo json_encode(["success" => false, "error" => "Failed to create playlist"]);
        exit();
    }

    // 3️⃣ Insert user-playlist mapping if not exists
    $stmt = $conn->prepare("SELECT id FROM user_playlists WHERE playlist_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $playlist_id, $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) { // Only insert if mapping doesn't exist
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO user_playlists (playlist_id, user_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $playlist_id, $user_id);
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Playlist created successfully"]);
        } else {
            echo json_encode(["success" => false, "error" => "Failed to link playlist to user"]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "Playlist already exists"]);
    }
    $stmt->close();
} elseif ($operation === 'AddSong') {
    
header('Content-Type: application/json'); // Set response type to JSON

$response = ["success" => false, "message" => "Invalid request"];

// 1️⃣ Get parameters
$playlist_id = isset($_GET['playlist_id']) ? $_GET['playlist_id'] : null;
$song_id = isset($_GET['song_id']) ? $_GET['song_id'] : null;
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

if ($playlist_id && $song_id && $user_id) {
    // 2️⃣ Get user_playlist ID
    $stmt = $conn->prepare("SELECT id FROM user_playlists WHERE user_id = ? AND playlist_id = ?");
    $stmt->bind_param("ii", $user_id, $playlist_id);
    $stmt->execute();
    $stmt->bind_result($user_playlist_id);
    $stmt->fetch();
    $stmt->close();

    if ($user_playlist_id) {
        // 3️⃣ Insert into song_playlists
        $stmt = $conn->prepare("INSERT IGNORE INTO song_playlists (user_playlist_id, song_id, user_id) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $user_playlist_id, $song_id, $user_id);
        
        if ($stmt->execute()) {
            $response["success"] = true;
            $response["message"] = "Song added to playlist!";
        } else {
            $response["message"] = "Failed to add song!";
        }
        $stmt->close();
    } else {
        $response["message"] = "Playlist not found!";
    }
} else {
    $response["message"] = "Missing parameters!";
}

// Return JSON response
echo json_encode($response);
} elseif ($operation === 'Deleteplaylist') {
    $playlist_id = isset($_GET['playlist_id']) ? (int)$_GET['playlist_id'] : null;
    $user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;
    
    header("Content-Type: application/json"); // Ensure JSON response
    if (!empty($playlist_id) && !empty($user_id)) {
        $stmt = $conn->prepare("DELETE FROM user_playlists WHERE playlist_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $playlist_id, $user_id);
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Playlist deleted successfully!"]);
        } else {
            echo json_encode(["success" => false, "error" => "Failed to delete playlist!"]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "error" => "Invalid parameters!"]);
    }
} 
// elseif ($operation === 'DeleteSong') {
//     $playlist_id = isset($_GET['playlist_id']) ? $_GET['playlist_id'] : null;
//     $song_id = isset($_GET['song_id']) ? $_GET['song_id'] : null;
//     $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

//     if (!empty($user_id)) {
//         $stmt = $conn->prepare("SELECT id FROM user_playlists WHERE playlist_id = ? AND user_id = ?");
//         $stmt->bind_param("ii", $playlist_id, $user_id);
//         $stmt->execute();
//         $stmt->bind_result($user_playlist_id);
//         $stmt->fetch();
//         $stmt->close();

//         if ($user_playlist_id) {
//             $stmt = $conn->prepare("DELETE FROM song_playlists WHERE user_playlist_id = ? AND song_id = ?");
//             $stmt->bind_param("ii", $user_playlist_id, $song_id);
//             if ($stmt->execute()) {
//                 echo "Song removed from playlist!";
//             } else {
//                 echo "Failed to remove song!";
//             }
//             $stmt->close();
//         } else {
//             echo "No matching record found.";
//         }
//     } else {
//         echo "Invalid parameters!";
//     }
// } 
elseif ($operation === 'DeleteSong') {
    $playlist_id = isset($_GET['playlist_id']) ? $_GET['playlist_id'] : null;
    $song_id = isset($_GET['song_id']) ? $_GET['song_id'] : null;
    $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

    header('Content-Type: application/json'); // Set response type to JSON

    if (!empty($user_id) && !empty($playlist_id) && !empty($song_id)) {
        $stmt = $conn->prepare("SELECT id FROM user_playlists WHERE playlist_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $playlist_id, $user_id);
        $stmt->execute();
        $stmt->bind_result($user_playlist_id);
        $stmt->fetch();
        $stmt->close();

        if ($user_playlist_id) {
            $stmt = $conn->prepare("DELETE FROM song_playlists WHERE user_playlist_id = ? AND song_id = ?");
            $stmt->bind_param("ii", $user_playlist_id, $song_id);
            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Song removed from playlist!"]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to remove song!"]);
            }
            $stmt->close();
        } else {
            echo json_encode(["success" => false, "message" => "No matching record found."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Invalid parameters!"]);
    }
}












elseif ($operation === 'DisplaySongs') {
    $playlist_name = $_GET['playlist_name'];
    $stmt = $conn->prepare("SELECT musicsongs.* FROM musicsongs 
        INNER JOIN song_playlists ON musicsongs.id = song_playlists.song_id 
        INNER JOIN user_playlists ON song_playlists.user_playlist_id = user_playlists.id 
        INNER JOIN playlist ON user_playlists.playlist_id = playlist.id 
        WHERE playlist.name = ?");
    $stmt->bind_param("s", $playlist_name);
    $stmt->execute();
    $result = $stmt->get_result();

    $songs = [];
    while ($row = $result->fetch_assoc()) {
        $songs[] = $row;
    }
    $stmt->close();
    echo json_encode($songs);
}


// Prepare SQL query to fetch all playlists for the given user
if ($operation === "viewplaylist") {
    $stmt = $conn->prepare("
    SELECT playlist.* 
    FROM playlist 
    INNER JOIN user_playlists ON playlist.id = user_playlists.playlist_id 
    WHERE user_playlists.user_id = ?");
    if ($stmt === false) {
        echo json_encode(["success" => false, "error" => "SQL error: " . $conn->error]);
        exit();
    }
    $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $playlists = [];
    while ($row = $result->fetch_assoc()) {
        $playlists[] = $row;
    }


    $stmt->close();

    // If no playlists found, return an empty array
    if (empty($playlists)) {
        echo json_encode(["success" => true, "message" => "No playlist found", "data" => []]);
    } else {
        echo json_encode(["success" => true, "data" => $playlists]);
    }
}
