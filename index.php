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
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="video" class="form-label">Select Video</label>
                    <input type="file" class="form-control" id="video" name="video" accept="video/*" required>
                </div>
                <button type="submit" class="btn btn-primary">Upload</button>
            </form>
            <div class="progress mt-3" style="display:none;">
                <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%">0%</div>
            </div>
            <div id="uploadStatus" class="mt-3"></div>
            <div class="mt-5">
                <h4>Upload Log</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>File Name</th>
                            <th>Upload Time</th>
                        </tr>
                    </thead>
                    <tbody id="logTableBody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function fetchLog() {
    fetch('./video_log.php')
        .then(response => response.text())
        .then(data => {
            const logEntries = data.trim().split('\n');
            const logTableBody = document.getElementById('logTableBody');
            logTableBody.innerHTML = '';
            logEntries.forEach(entry => {
                const parts = entry.split(' at ');
                const fileName = parts[0].replace('Uploaded: ', '');
                const uploadTime = parts[1];
                const row = `<tr><td>${fileName}</td><td>${uploadTime}</td></tr>`;
                logTableBody.innerHTML += row;
            });
        });
}

// Fetch the log on page load
fetchLog();

document.getElementById('uploadForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const xhr = new XMLHttpRequest();
    
    // Show progress bar
    document.querySelector('.progress').style.display = 'block';
    const progressBar = document.getElementById('progressBar');
    const uploadStatus = document.getElementById('uploadStatus');
    
    xhr.upload.onprogress = function(event) {
        if (event.lengthComputable) {
            const percentComplete = (event.loaded / event.total) * 100;
            progressBar.style.width = percentComplete + '%';
            progressBar.textContent = percentComplete.toFixed(2) + '%';
        }
    };
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            uploadStatus.innerHTML = '<div class="alert alert-success">Video uploaded successfully!</div>';
            fetchLog(); // Update log table after successful upload
        } else {
            uploadStatus.innerHTML = '<div class="alert alert-danger">Upload failed</div>';
        }
    };
    
    xhr.open('POST', 'upload.php', true);
    xhr.send(formData);
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
