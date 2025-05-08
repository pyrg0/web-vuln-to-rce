<?php
$upload_dir = "uploads/";
$message = '';

if (isset($_FILES['file'])) {
    $file_name = $_FILES['file']['name'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $file_size = $_FILES['file']['size'];
    $file_error = $_FILES['file']['error'];
    
    // Intentionally weak validation
    if ($file_error === UPLOAD_ERR_OK) {
        $upload_path = $upload_dir . basename($file_name);
        
        // Move the uploaded file
        if (move_uploaded_file($file_tmp, $upload_path)) {
            $message = "File uploaded successfully!<br>";
            $message .= "Access your file at: <a href='$upload_path'>$upload_path</a>";
        } else {
            $message = "Error uploading file!";
        }
    } else {
        $message = "Upload error: $file_error";
    }
}

$flag = file_get_contents('flag.txt');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vulnerable File Upload</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .container { background: #f9f9f9; padding: 20px; border-radius: 5px; }
        .success { color: green; }
        .error { color: red; }
        pre { background: #eee; padding: 10px; border-radius: 3px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Profile Picture Upload</h1>
        <p>Upload your profile picture (JPEG, PNG, GIF allowed):</p>
        
        <form action="" method="post" enctype="multipart/form-data">
            <input type="file" name="file" id="file">
            <input type="submit" value="Upload Image" name="submit">
        </form>
        
        <?php if (!empty($message)): ?>
            <div class="<?= strpos($message, 'successfully') !== false ? 'success' : 'error' ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>
        
        <h2>Upload Directory Contents:</h2>
        <pre><?php
            if (is_dir($upload_dir)) {
                $files = scandir($upload_dir);
                foreach ($files as $file) {
                    if ($file !== '.' && $file !== '..') {
                        echo htmlspecialchars($file) . "\n";
                    }
                }
            }
        ?></pre>
        
        <!-- Debug info for CTF players -->
        <div style="margin-top: 30px; padding: 10px; background: #ffecec; border-left: 3px solid red;">
            <h3>Debug Information:</h3>
            <p>Upload directory: <?= realpath($upload_dir) ?></p>
            <p>Current path: <?= __FILE__ ?></p>
        </div>
    </div>
</body>
</html>
