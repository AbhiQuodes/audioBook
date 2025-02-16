<?php
// Connect to database
include "./../userAuthentication/config.php";

// Get search query and category from URL parameters
$searchQuery = isset($_GET["query"]) ? trim($_GET["query"]) : "";
$category = isset($_GET["category"]) ? $_GET["category"] : "All";

// Initialize an empty array to store song details
$songDetails = [];

// Fetch results if search query exists
if ($searchQuery) {
    $sql = "SELECT * FROM musicSongs WHERE title LIKE ?";
    if ($category !== "All") {
        $sql .= " AND category = ?";
    }

    $stmt = $conn->prepare($sql);
    $searchPattern = "%$searchQuery%";

    if ($category !== "All") {
        $stmt->bind_param("ss", $searchPattern, $category);
    } else {
        $stmt->bind_param("s", $searchPattern);
    }

    // Execute query
    $stmt->execute();
    $result = $stmt->get_result();

    // Store results in the array
    while ($row = $result->fetch_assoc()) {
        $songDetails[] = $row;
    }

    $stmt->close();
}

$conn->close();
?>

<!-- Songs Display -->
<?php if (!empty($songDetails)): ?>
    <?php foreach ($songDetails as $song): ?> 
        <li class="music-box-list-item" key="<?= htmlspecialchars($song['id']) ?>">
            <a href="./songplay.php?id=<?= htmlspecialchars($song['id']) ?>">
                <div class="img-box">
                    <img src="./../images/musicCardImg1.webp" class="music-image" alt="music-img">
                </div>
                <div class="text"><?= htmlspecialchars($song['title']) ?></div>
            </a>
        </li>
    <?php endforeach; ?>
<?php else: ?>
    <p class="no-results">No songs found</p>
<?php endif; ?>
