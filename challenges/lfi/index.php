<?php
if (isset($_GET['file'])) {
    $file = $_GET['file'];
    // Remove directory traversal protection to make it more vulnerable
    $file_path = $file;
    
    if (file_exists($file_path)) {
        // Check if it's a directory
        if (is_dir($file_path)) {
            $dir_content = "<div class='content-header'>
                                <div class='type-indicator directory'><i class='fas fa-folder-open'></i></div>
                                <h2>Directory: <span class='highlight-path'>$file_path</span></h2>
                            </div>";
            $dir_content .= "<div class='directory-list'>";
            foreach (scandir($file_path) as $item) {
                if ($item !== "." && $item !== "..") {
                    $is_dir = is_dir("$file_path/$item");
                    $icon = $is_dir ? "<i class='fas fa-folder'></i>" : "<i class='fas fa-file-alt'></i>";
                    $dir_content .= "<div class='dir-item'>
                                        <span class='item-icon'>$icon</span>
                                        <a href='?file=" . urlencode("$file_path/$item") . "'>$item</a>
                                    </div>";
                }
            }
            $dir_content .= "</div>";
            $content = $dir_content;
        } else {
            // Display file contents
            $file_ext = pathinfo($file_path, PATHINFO_EXTENSION);
            $icon_class = "fa-file-alt"; // Default
            
            // Set icon based on extension
            if (in_array($file_ext, ['php', 'py', 'js', 'html', 'css'])) {
                $icon_class = "fa-file-code";
            } elseif (in_array($file_ext, ['txt', 'log'])) {
                $icon_class = "fa-file-alt";
            } elseif (in_array($file_ext, ['jpg', 'png', 'gif', 'svg'])) {
                $icon_class = "fa-file-image";
            } elseif (in_array($file_ext, ['pdf'])) {
                $icon_class = "fa-file-pdf";
            } elseif (in_array($file_ext, ['zip', 'tar', 'gz', 'rar'])) {
                $icon_class = "fa-file-archive";
            }
            
            $file_content = "<div class='content-header'>
                                <div class='type-indicator file'><i class='fas $icon_class'></i></div>
                                <h2>File: <span class='highlight-path'>$file_path</span></h2>
                            </div>";
            $file_content .= "<div class='file-meta'>
                                <span><i class='fas fa-calendar-alt'></i> Modified: " . date("Y-m-d H:i:s", filemtime($file_path)) . "</span>
                                <span><i class='fas fa-weight'></i> Size: " . filesize($file_path) . " bytes</span>
                            </div>";
            $file_content .= "<div class='terminal-container'>
                                <div class='terminal-header'>
                                    <span class='terminal-title'>FILE CONTENTS</span>
                                    <div class='terminal-controls'>
                                        <span class='terminal-circle'></span>
                                        <span class='terminal-circle'></span>
                                        <span class='terminal-circle'></span>
                                    </div>
                                </div>
                                <div class='terminal-body'>
                                    <pre class='file-contents'>" . htmlspecialchars(file_get_contents($file_path)) . "</pre>
                                </div>
                            </div>";
            $content = $file_content;
        }
    } else {
        $content = "<div class='error-container'>
                        <div class='error-icon'><i class='fas fa-exclamation-triangle'></i></div>
                        <div class='error-message'>File not found: <span class='highlight-path'>$file_path</span></div>
                    </div>";
    }
} else {
    // Default files to show
    $default_files = [
        '/etc/passwd' => 'System Users',
        '/etc/hosts' => 'Hosts File',
        '/home/' => 'Home Directory',
        'secret/flag.txt' => 'Secret Flag',
        '/var/log/apache2/access.log' => 'Apache Access Logs',
        '/var/www/html/index.php' => 'Web Root Index',
        '/proc/self/environ' => 'Process Environment'
    ];
    
    $content = "";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced File Viewer</title>
    
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Font - Share Tech Mono & Orbitron -->
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #fff;
            --primary-dark: #eaeaea;
            --secondary: #00ffff;
            --dark: #0a0a0a;
            --darker: #050505;
            --accent: #ff00ff;
            --warning: #ff8800;
            --danger: #ff0000;
            --light-text: #f5f5f5;
            --dark-text: #252525;
            --border-glow: 0 0 10px var(--primary), 0 0 20px rgba(0, 255, 0, 0.2);
            --text-glow: 0 0 5px var(--primary);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Share Tech Mono', monospace;
            background-color: var(--dark);
            color: var(--light-text);
            line-height: 1.6;
            background-image: 
                radial-gradient(circle at 25px 25px, rgba(0, 255, 0, 0.1) 2px, transparent 0),
                linear-gradient(rgba(0, 255, 0, 0.05) 1px, transparent 0);
            background-size: 50px 50px, 50px 50px;
            position: relative;
            min-height: 100vh;
            padding-bottom: 80px;
        }
        
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: repeating-linear-gradient(
                transparent 0%,
                rgba(0, 0, 0, 0.1) 50%,
                transparent 100%
            );
            background-size: 100% 2px;
            pointer-events: none;
            z-index: 999;
            animation: scanline 10s linear infinite;
            opacity: 0.3;
        }
        
        @keyframes scanline {
            0% { background-position: 0 0; }
            100% { background-position: 0 100%; }
        }
        
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 0;
        }
        
        header {
            text-align: center;
            border-bottom: 1px solid var(--primary);
            padding-bottom: 20px;
            margin-bottom: 30px;
            box-shadow: var(--border-glow);
        }
        
        h1 {
            padding-top: 15px;
            font-family: 'Orbitron', sans-serif;
            color: var(--primary);
            text-shadow: var(--text-glow);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 10px;
            align-items: center;
        }
        
        h1 i {
            margin-right: 15px;
            font-size: 2rem;
        }
        
        .subtitle {
            color: var(--secondary);
            font-size: 1rem;
            margin-bottom: 10px;
            opacity: 0.9;
        }
        
        .file-input-container {
            background-color: var(--darker);
            border: 1px solid var(--primary-dark);
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
            position: relative;
            overflow: hidden;
        }
        
        .file-input-container::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 50%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(0, 255, 0, 0.1),
                transparent
            );
            animation: scan 3s linear infinite;
        }
        
        @keyframes scan {
            0% { left: -100%; }
            100% { left: 200%; }
        }
        
        form {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }
        
        input[type="text"] {
            flex: 1;
            background-color: var(--dark);
            border: 1px solid var(--primary-dark);
            color: var(--primary);
            padding: 10px 15px;
            font-family: 'Share Tech Mono', monospace;
            font-size: 1rem;
            border-radius: 3px;
            outline: none;
            transition: all 0.3s ease;
        }
        
        input[type="text"]:focus {
            border-color: var(--primary);
            box-shadow: var(--border-glow);
        }
        
        input[type="text"]::placeholder {
            color: rgba(0, 255, 0, 0.5);
        }
        
        button {
            background-color: var(--primary-dark);
            color: var(--dark-text);
            border: none;
            padding: 10px 20px;
            font-family: 'Share Tech Mono', monospace;
            font-size: 1rem;
            border-radius: 3px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        button:hover {
            background-color: var(--primary);
            box-shadow: var(--border-glow);
        }
        
        .hint {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 10px;
        }
        
        .hint strong {
            color: var(--warning);
        }
        
        .content-section {
            background-color: var(--darker);
            border: 1px solid var(--primary-dark);
            border-radius: 5px;
            overflow: hidden;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
        }
        
        .content-header {
            background: linear-gradient(90deg, var(--dark), var(--darker));
            padding: 15px;
            border-bottom: 1px solid var(--primary-dark);
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .type-indicator {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        
        .type-indicator.directory {
            background-color: var(--warning);
            color: var(--dark-text);
        }
        
        .type-indicator.file {
            background-color: var(--secondary);
            color: var(--dark-text);
        }
        
        h2 {
            font-size: 1.2rem;
            font-weight: normal;
            flex: 1;
        }
        
        .highlight-path {
            color: var(--primary);
            background-color: rgba(0, 255, 0, 0.1);
            padding: 3px 6px;
            border-radius: 3px;
            font-family: monospace;
        }
        
        .file-meta {
            background-color: rgba(0, 0, 0, 0.3);
            padding: 8px 15px;
            font-size: 0.85rem;
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid var(--primary-dark);
        }
        
        .terminal-container {
            background-color: var(--darker);
            margin: 15px;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .terminal-header {
            background-color: var(--dark-text);
            padding: 8px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .terminal-title {
            color: var(--light-text);
            font-size: 0.8rem;
            font-weight: bold;
        }
        
        .terminal-controls {
            display: flex;
            gap: 5px;
        }
        
        .terminal-circle {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #888;
        }
        
        .terminal-circle:nth-child(1) {
            background-color: #ff5f57;
        }
        
        .terminal-circle:nth-child(2) {
            background-color: #febc2e;
        }
        
        .terminal-circle:nth-child(3) {
            background-color: #28c840;
        }
        
        .terminal-body {
            padding: 15px;
            max-height: 500px;
            overflow-y: auto;
            background-color: var(--dark);
        }
        
        .file-contents {
            margin: 0;
            font-family: 'Share Tech Mono', monospace;
            color: var(--light-text);
            line-height: 1.5;
            font-size: 0.9rem;
            overflow-x: auto;
        }
        
        .file-contents::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        .file-contents::-webkit-scrollbar-track {
            background: var(--darker);
        }
        
        .file-contents::-webkit-scrollbar-thumb {
            background-color: var(--primary-dark);
            border-radius: 4px;
        }
        
        .directory-list {
            padding: 15px;
        }
        
        .dir-item {
            padding: 8px 15px;
            border-bottom: 1px solid rgba(0, 255, 0, 0.1);
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
        }
        
        .dir-item:last-child {
            border-bottom: none;
        }
        
        .dir-item:hover {
            background-color: rgba(0, 255, 0, 0.05);
        }
        
        .item-icon {
            margin-right: 10px;
            color: var(--secondary);
            width: 20px;
            text-align: center;
        }
        
        .dir-item a {
            color: var(--light-text);
            text-decoration: none;
            transition: all 0.2s ease;
            flex: 1;
        }
        
        .dir-item a:hover {
            color: var(--primary);
            text-shadow: var(--text-glow);
            text-decoration: underline;
        }
        
        .suggested-files {
            background-color: var(--darker);
            border: 1px solid var(--primary-dark);
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
        }
        
        .suggested-files h3 {
            color: var(--secondary);
            margin-bottom: 15px;
            font-size: 1.2rem;
            border-bottom: 1px solid var(--primary-dark);
            padding-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .file-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 15px;
        }
        
        .file-item {
            background: linear-gradient(135deg, rgba(0, 128, 0, 0.1), rgba(0, 0, 0, 0));
            border: 1px solid var(--primary-dark);
            border-radius: 5px;
            padding: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .file-item:hover {
            transform: translateY(-3px);
            box-shadow: var(--border-glow);
            border-color: var(--primary);
        }
        
        .file-item-title {
            color: var(--primary);
            font-weight: bold;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .file-item-path {
            font-size: 0.85rem;
            color: var(--light-text);
            opacity: 0.7;
            font-family: monospace;
            word-break: break-all;
        }
        
        .error-container {
            background-color: rgba(255, 0, 0, 0.1);
            border: 1px solid var(--danger);
            border-radius: 5px;
            padding: 20px;
            margin: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .error-icon {
            font-size: 2rem;
            color: var(--danger);
        }
        
        .error-message {
            font-size: 1rem;
        }
        
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: var(--secondary);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }
        
        .back-link:hover {
            color: var(--primary);
            text-shadow: var(--text-glow);
        }
        
        .back-link i {
            margin-right: 5px;
        }
        
        footer {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: var(--darker);
            border-top: 1px solid var(--primary-dark);
            padding: 15px 0;
            text-align: center;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.5);
        }
        
        .status-indicator {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: var(--dark);
            border: 1px solid var(--primary-dark);
            border-radius: 5px;
            padding: 8px 15px;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.3);
            z-index: 100;
        }
        
        .status-led {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: var(--primary);
            animation: blink 1.5s infinite alternate;
        }
        
        @keyframes blink {
            0% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container {
                width: 95%;
            }
            
            .file-list {
                grid-template-columns: 1fr;
            }
            
            form {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="text-center">
            <h1><i class="fas fa-file-code"></i> Advanced File Viewer</h1>
            <div class="subtitle">Explore system files with LFI capabilities</div>
        </header>
        
        <div class="file-input-container">
            <form method="GET">
                <input type="text" name="file" placeholder="Enter file path (e.g., /etc/passwd, ../../../etc/passwd)" value="<?php echo isset($_GET['file']) ? htmlspecialchars($_GET['file']) : ''; ?>" required>
                <button type="submit"><i class="fas fa-search"></i> View File</button>
            </form>
            <div class="hint">
                <strong>Hint:</strong> Try to use directory traversal techniques to access sensitive files
            </div>
        </div>
        
        <?php if (isset($_GET['file'])): ?>
            <div class="content-section">
                <?php echo $content; ?>
            </div>
            
            <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="back-link"><i class="fas fa-arrow-left"></i> Back to home</a>
        <?php endif; ?>
    </div>
    
    <div class="status-indicator">
        <div class="status-led"></div>
        <span>LFI Simulation Active</span>
    </div>
    
    <footer>
        Advanced File Viewer v1.0 | <span style="color: var(--primary);">Vulnerability:</span> Local File Inclusion (LFI)
    </footer>
</body>
</html>