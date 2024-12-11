<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = './uploads/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Check if a file was uploaded
    if (!isset($_FILES['video']) || $_FILES['video']['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo json_encode(['error' => 'No file uploaded or upload error occurred']);
        exit;
    }

    $video = $_FILES['video'];
    $targetFile = $uploadDir . basename($video['name']);

    // Check file size (optional - set a max file size)
    $maxFileSize = 1500 * 1024 * 1024; // 1500MB 
    if ($video['size'] > $maxFileSize) {
        http_response_code(400);
        echo json_encode(['error' => 'File is too large']);
        exit;
    }

    // Move uploaded file
    if (move_uploaded_file($video['tmp_name'], $targetFile)) {
        // Log the upload
        $logEntry = "Uploaded: " . basename($video['name']) . " at " . date('Y-m-d H:i:s') . "\n";
        file_put_contents('video_log.txt', $logEntry, FILE_APPEND);
        
        echo json_encode(['success' => true, 'message' => 'Video uploaded successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to move uploaded file']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>