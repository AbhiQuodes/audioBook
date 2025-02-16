<?php
include './../userAuthentication/config.php'; // Database connection

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);
$downloadId = $data['downloadId'] ?? 0;

if ($downloadId) {
    $sql = "DELETE FROM downloads WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $downloadId);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "invalid";
}
?>
