<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Upload with Progress</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="card">
        <div class="card-header">Upload a Video</div>
        <div class="card-body">
            <form action="upload.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="video" class="form-label">Select Video</label>
                    <input type="file" class="form-control" id="video" name="video" accept="video/*" required>
                    <input type="hidden" name="UPLOAD_IDENTIFIER" value="<?php echo uniqid(); ?>">
                </div>
                <button type="submit" class="btn btn-primary">Upload</button>
            </form>
            <div id="progress" class="mt-3"></div>
        </div>
    </div>
</div>
<script>
    const uniqueId = document.querySelector('input[name="UPLOAD_IDENTIFIER"]').value;

    function updateProgress() {
        fetch('upload_progress.php?upload_id=' + uniqueId)
            .then(response => response.json())
            .then(data => {
                document.getElementById('progress').innerText = `Progress: ${data.progress.toFixed(2)}%`;
                if (data.progress < 100) {
                    setTimeout(updateProgress, 1000);
                }
            });
    }

    updateProgress();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
