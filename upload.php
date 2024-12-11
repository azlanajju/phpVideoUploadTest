<?php
session_start(); // Start the session to track upload progress

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = './uploads/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $video = $_FILES['video'];
    $targetFile = $uploadDir . basename($video['name']);
    $uploadId = $_POST['UPLOAD_IDENTIFIER'];

    // Store the upload ID and initial progress information in the session
    $_SESSION['upload_progress'][$uploadId] = [
        'targetFile' => $targetFile,
        'tmp_name' => $video['tmp_name'],
        'total_size' => $video['size']
    ];

    if (move_uploaded_file($video['tmp_name'], $targetFile)) {
        $logEntry = "<?php\n// Log of uploaded videos\n" . "Uploaded: " . basename($video['name']) . "\n";
        file_put_contents('video_log.php', $logEntry, FILE_APPEND);
        echo '<div class="alert alert-success">Video uploaded successfully!</div>';
    } else {
        echo '<div class="alert alert-danger">Failed to upload video.</div>';
    }
}
?>
