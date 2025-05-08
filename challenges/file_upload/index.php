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
            $message = "<div class='upload-success'>
                            <i class='fas fa-check-circle'></i>
                            <div class='message-content'>
                                <div>File uploaded successfully!</div>
                                <div>Access your file at: <a href='$upload_path' target='_blank'>$upload_path</a></div>
                            </div>
                        </div>";
        } else {
            $message = "<div class='upload-error'>
                            <i class='fas fa-times-circle'></i>
                            <div class='message-content'>Error uploading file!</div>
                        </div>";
        }
    } else {
        $message = "<div class='upload-error'>
                        <i class='fas fa-exclamation-triangle'></i>
                        <div class='message-content'>Upload error: $file_error</div>
                    </div>";
    }
}

$flag = file_get_contents('flag.txt');

// Get directory contents
$dir_contents = "";
if (is_dir($upload_dir)) {
    $files = scandir($upload_dir);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $file_extension = pathinfo($file, PATHINFO_EXTENSION);
            $icon_class = "fa-file";
            
            // Set icon based on extension
            if (in_array(strtolower($file_extension), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg'])) {
                $icon_class = "fa-file-image";
            } elseif (in_array(strtolower($file_extension), ['php', 'phtml', 'php5', 'phps'])) {
                $icon_class = "fa-file-code";
            } elseif (in_array(strtolower($file_extension), ['zip', 'rar', 'tar', 'gz'])) {
                $icon_class = "fa-file-archive";
            } elseif (in_array(strtolower($file_extension), ['txt', 'log'])) {
                $icon_class = "fa-file-alt";
            } elseif (in_array(strtolower($file_extension), ['js', 'html', 'css', 'xml'])) {
                $icon_class = "fa-file-code";
            } elseif (in_array(strtolower($file_extension), ['pdf'])) {
                $icon_class = "fa-file-pdf";
            }
            
            $file_url = $upload_dir . $file;
            $file_size = filesize($file_url);
            $file_time = date("Y-m-d H:i:s", filemtime($file_url));
            
            $dir_contents .= "<div class='file-item'>
                                <div class='file-icon'><i class='fas $icon_class'></i></div>
                                <div class='file-details'>
                                    <div class='file-name'><a href='$file_url' target='_blank'>$file</a></div>
                                    <div class='file-meta'>
                                        <span><i class='fas fa-weight'></i> $file_size bytes</span>
                                        <span><i class='fas fa-clock'></i> $file_time</span>
                                    </div>
                                </div>
                             </div>";
        }
    }
    
    if (empty($dir_contents)) {
        $dir_contents = "<div class='empty-directory'>
                            <i class='fas fa-folder-open'></i>
                            <p>No files uploaded yet</p>
                         </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vulnerable File Upload</title>
    
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Font - Fira Code & Chakra Petch -->
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;600&family=Chakra+Petch:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #00ff88;
            --primary-dark: #00cc66;
            --secondary: #0088ff;
            --accent: #ff00aa;
            --dark: #121212;
            --darker: #0a0a0a;
            --light: #f8f8f8;
            --warning: #ffbb00;
            --danger: #ff3333;
            --success: #00cc66;
            --border-glow: 0 0 10px var(--primary), 0 0 20px rgba(0, 255, 136, 0.2);
            --text-glow: 0 0 5px var(--primary);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Fira Code', monospace;
            background-color: var(--dark);
            color: var(--light);
            line-height: 1.6;
            position: relative;
            min-height: 100vh;
            padding-bottom: 80px;
            background-image: 
                linear-gradient(rgba(0, 255, 136, 0.03) 1.5px, transparent 1.5px),
                linear-gradient(90deg, rgba(0, 255, 136, 0.03) 1.5px, transparent 1.5px);
            background-size: 30px 30px;
        }
        
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                linear-gradient(
                    var(--dark), 
                    transparent 20%, 
                    transparent 80%, 
                    var(--dark)
                );
            pointer-events: none;
            z-index: 1;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 20px;
            position: relative;
            z-index: 2;
        }
        
        .card {
            background-color: var(--darker);
            border: 1px solid rgba(0, 255, 136, 0.2);
            border-radius: 10px;
            margin-bottom: 30px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            position: relative;
        }
        
        .card-header {
            padding: 20px;
            background: linear-gradient(90deg, rgba(0, 204, 102, 0.1), rgba(0, 0, 0, 0));
            border-bottom: 1px solid rgba(0, 255, 136, 0.2);
            position: relative;
        }
        
        .card-header h2 {
            font-family: 'Chakra Petch', sans-serif;
            color: var(--primary);
            margin: 0;
            font-size: 1.4rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .title-badge {
            position: absolute;
            top: 0;
            right: 30px;
            background-color: var(--accent);
            color: white;
            font-size: 0.7rem;
            padding: 4px 8px;
            border-bottom-left-radius: 6px;
            border-bottom-right-radius: 6px;
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
            box-shadow: 0 2px 10px rgba(255, 0, 170, 0.4);
        }
        
        h1 {
            font-family: 'Chakra Petch', sans-serif;
            color: var(--primary);
            text-shadow: var(--text-glow);
            font-size: 2.5rem;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: relative;
            display: inline-block;
        }
        
        h1::after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, var(--primary), transparent);
        }
        
        p {
            margin-bottom: 20px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.95rem;
        }
        
        .upload-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
            border: 2px dashed rgba(0, 255, 136, 0.3);
            border-radius: 8px;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            margin-bottom: 20px;
            background-color: rgba(0, 255, 136, 0.05);
        }
        
        .upload-area:hover {
            border-color: var(--primary);
            box-shadow: var(--border-glow);
        }
        
        .upload-area i {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 15px;
        }
        
        .upload-form {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            max-width: 400px;
        }
        
        .file-input-wrapper {
            position: relative;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .file-input {
            opacity: 0;
            width: 100%;
            height: 100%;
            position: absolute;
            cursor: pointer;
            z-index: 2;
        }
        
        .file-input-label {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px;
            background-color: rgba(0, 136, 255, 0.1);
            border: 1px solid rgba(0, 136, 255, 0.3);
            border-radius: 6px;
            color: var(--secondary);
            font-size: 0.9rem;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .file-input-label:hover {
            background-color: rgba(0, 136, 255, 0.2);
            border-color: var(--secondary);
        }
        
        .file-input-label i {
            font-size: 1.2rem;
            margin-right: 8px;
        }
        
        .file-name {
            margin-top: 10px;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
            word-break: break-all;
            display: none;
        }
        
        .upload-btn {
            background: linear-gradient(45deg, var(--primary-dark), var(--primary));
            color: var(--dark);
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-family: 'Chakra Petch', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 255, 136, 0.2);
            letter-spacing: 1px;
            margin-top: 10px;
            width: 100%;
        }
        
        .upload-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 255, 136, 0.3);
        }
        
        .upload-btn:active {
            transform: translateY(1px);
            box-shadow: 0 2px 5px rgba(0, 255, 136, 0.2);
        }
        
        .upload-btn i {
            margin-right: 8px;
        }
        
        .upload-success,
        .upload-error {
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }
        
        .upload-success {
            background-color: rgba(0, 204, 102, 0.1);
            border-left: 4px solid var(--success);
        }
        
        .upload-error {
            background-color: rgba(255, 51, 51, 0.1);
            border-left: 4px solid var(--danger);
        }
        
        .upload-success i,
        .upload-error i {
            font-size: 1.5rem;
        }
        
        .upload-success i {
            color: var(--success);
        }
        
        .upload-error i {
            color: var(--danger);
        }
        
        .message-content {
            flex: 1;
        }
        
        .message-content a {
            color: var(--secondary);
            text-decoration: none;
            transition: all 0.2s ease;
            border-bottom: 1px dashed var(--secondary);
        }
        
        .message-content a:hover {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }
        
        .directory-container {
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 6px;
            margin-top: 15px;
        }
        
        .directory-header {
            padding: 12px 15px;
            background-color: rgba(0, 0, 0, 0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top-left-radius: 6px;
            border-top-right-radius: 6px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .directory-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            color: var(--light);
        }
        
        .file-list {
            padding: 15px;
            max-height: 300px;
            overflow-y: auto;
        }
        
        .file-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.2s ease;
        }
        
        .file-item:last-child {
            border-bottom: none;
        }
        
        .file-item:hover {
            background-color: rgba(255, 255, 255, 0.03);
        }
        
        .file-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: var(--secondary);
            margin-right: 15px;
        }
        
        .file-details {
            flex: 1;
        }
        
        .file-name {
            font-weight: 500;
            color: var(--light);
            margin-bottom: 5px;
            display: block;
        }
        
        .file-name a {
            color: var(--light);
            text-decoration: none;
            transition: all 0.2s ease;
        }
        
        .file-name a:hover {
            color: var(--primary);
        }
        
        .file-meta {
            display: flex;
            gap: 15px;
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.5);
        }
        
        .file-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .empty-directory {
            padding: 30px;
            text-align: center;
            color: rgba(255, 255, 255, 0.5);
        }
        
        .empty-directory i {
            font-size: 2rem;
            margin-bottom: 10px;
            color: rgba(255, 255, 255, 0.3);
        }
        
        .debug-card {
            margin-top: 30px;
            border-left: 4px solid var(--warning);
            background-color: rgba(255, 187, 0, 0.05);
        }
        
        .debug-card .card-header {
            background: linear-gradient(90deg, rgba(255, 187, 0, 0.1), rgba(0, 0, 0, 0));
            border-bottom-color: rgba(255, 187, 0, 0.2);
        }
        
        .debug-card .card-header h2 {
            color: var(--warning);
        }
        
        .debug-info {
            font-family: 'Fira Code', monospace;
            background-color: rgba(0, 0, 0, 0.3);
            padding: 15px;
            border-radius: 6px;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 15px;
        }
        
        .debug-info p {
            margin-bottom: 10px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        
        .debug-info p:last-child {
            margin-bottom: 0;
        }
        
        .debug-info p i {
            color: var(--warning);
        }
        
        .hint-banner {
            margin-top: 15px;
            padding: 10px 15px;
            border-radius: 6px;
            background-color: rgba(255, 0, 170, 0.1);
            border-left: 4px solid var(--accent);
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .hint-banner i {
            color: var(--accent);
            font-size: 1.2rem;
        }
        
        .tooltip {
            position: relative;
            display: inline-block;
            cursor: help;
        }
        
        .tooltip .tooltiptext {
            visibility: hidden;
            width: 200px;
            background-color: rgba(0, 0, 0, 0.9);
            color: var(--light);
            text-align: center;
            border-radius: 6px;
            padding: 10px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 0.8rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .tooltip .tooltiptext::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: rgba(0, 0, 0, 0.9) transparent transparent transparent;
        }
        
        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }
        
        footer {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 20px;
            text-align: center;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.5);
            background-color: var(--darker);
            border-top: 1px solid rgba(0, 255, 136, 0.1);
        }
        
        .vulnerable-badge {
            padding: 3px 8px;
            background-color: var(--danger);
            color: white;
            border-radius: 4px;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-left: 5px;
            font-weight: 700;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            
            h1 {
                font-size: 1.8rem;
            }
            
            .upload-area {
                padding: 20px 10px;
            }
            
            .upload-area i {
                font-size: 2rem;
            }
            
            .file-meta {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Profile Picture Upload</h1>
        
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-cloud-upload-alt"></i> File Upload System</h2>
                <div class="title-badge">Vulnerable</div>
            </div>
            <div class="card-body">
                <p>Upload your profile picture or any file you'd like to share. Our system accepts various file formats including JPEG, PNG, and GIF.</p>
                
                <div class="upload-area">
                    <i class="fas fa-file-upload"></i>
                    <form action="" method="post" enctype="multipart/form-data" class="upload-form">
                        <div class="file-input-wrapper">
                            <input type="file" name="file" id="file" class="file-input">
                            <label for="file" class="file-input-label">
                                <i class="fas fa-search"></i> Browse Files
                            </label>
                            <div id="file-name" class="file-name">No file selected</div>
                        </div>
                        <button type="submit" class="upload-btn">
                            <i class="fas fa-upload"></i> Upload File
                        </button>
                    </form>
                </div>
                
                <div class="hint-banner">
                    <i class="fas fa-lightbulb"></i>
                    <div>
                        <strong>Hint:</strong> Can you upload a file that allows you to execute code on the server?
                    </div>
                </div>
                
                <?php if (!empty($message)): ?>
                    <?= $message ?>
                <?php endif; ?>
                
                <div class="card">
                    <div class="card-header">
                        <h2><i class="fas fa-folder"></i> Uploaded Files</h2>
                    </div>
                    <div class="card-body">
                        <div class="directory-container">
                            <div class="directory-header">
                                <div class="directory-title">
                                    <i class="fas fa-folder-open"></i>
                                    <span><?= realpath($upload_dir) ?></span>
                                </div>
                                <div class="tooltip">
                                    <i class="fas fa-info-circle"></i>
                                    <span class="tooltiptext">This shows all uploaded files in the system. Try uploading different file types.</span>
                                </div>
                            </div>
                            <div class="file-list">
                                <?= $dir_contents ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
    <footer>
        <p>Vulnerable File Upload System v1.0 <span class="vulnerable-badge">Insecure</span></p>
        <p>Cybersecurity Training Lab &copy; 2025 | For Educational Purposes Only</p>
    </footer>
    
    <script>
        // Show selected filename
        document.getElementById('file').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'No file selected';
            const fileNameElement = document.getElementById('file-name');
            fileNameElement.textContent = fileName;
            fileNameElement.style.display = 'block';
        });
    </script>
</body>
</html>