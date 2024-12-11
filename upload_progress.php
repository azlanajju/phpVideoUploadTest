<?php
session_start(); // Ensure the session is started

$uploadId = $_GET['upload_id'];

// Check if the upload progress session exists
if (isset($_SESSION['upload_progress'][$uploadId])) {
    $fileInfo = $_SESSION['upload_progress'][$uploadId];
    $uploadedBytes = file_exists($fileInfo['targetFile']) ? filesize($fileInfo['targetFile']) : 0;
    $totalBytes = $fileInfo['total_size'];
    $progress = ($uploadedBytes / $totalBytes) * 100;

    echo json_encode(['progress' => $progress]);
} else {
    echo json_encode(['progress' => 0]);
}
