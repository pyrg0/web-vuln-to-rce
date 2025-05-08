<?php
// Disable directory traversal protections
function isAllowedPath($path) {
    // Intentionally vulnerable - allows any path
    return true;
}

if (isset($_GET['file'])) {
    $file = $_GET['file'];
    
    // Remove any basic protections
    $file = str_replace('../', '', $file); // Weak protection that can be bypassed
    
    if (isAllowedPath($file)) {
        if (file_exists($file)) {
            header('Content-Type: text/plain');
            readfile($file);
            exit();
        } else {
            $error = "File not found!";
        }
    } else {
        $error = "Access denied!";
    }
}

$flag = file_get_contents('flag.txt');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super File Viewer</title>
    
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts - JetBrains Mono & Press Start 2P -->
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;800&family=Press+Start+2P&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --neon-blue: #00b2ff;
            --neon-purple: #8a2be2;
            --neon-pink: #ff00aa;
            --dark-bg: #0b0b0e;
            --darker-bg: #070709;
            --card-bg: #111117;
            --text-primary: #ffffff;
            --text-secondary: #b3b3cc;
            --terminal-green: #00ff66;
            --danger: #ff3655;
            --success: #00ffc3;
            --hover-blue: #38d6ff;
            --glow-blue: 0 0 10px var(--neon-blue), 0 0 20px rgba(0, 178, 255, 0.3);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'JetBrains Mono', monospace;
            background-color: var(--dark-bg);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
            position: relative;
            background-image: 
                radial-gradient(circle at 30px 30px, rgba(138, 43, 226, 0.07) 4px, transparent 0),
                radial-gradient(circle at 15px 15px, rgba(0, 178, 255, 0.05) 2px, transparent 0);
            background-size: 30px 30px, 15px 15px;
        }
        
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(0deg, var(--dark-bg), transparent 15%);
            pointer-events: none;
            z-index: 1;
        }
        
        /* Scanline effect */
        body::after {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 2;
            pointer-events: none;
            background: linear-gradient(
                rgba(18, 16, 16, 0) 50%, 
                rgba(0, 0, 0, 0.1) 50%
            );
            background-size: 100% 4px;
            opacity: 0.2;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 50px 20px;
            position: relative;
            z-index: 3;
        }
        
        /* Glowing title */
        .title-container {
            margin-bottom: 30px;
            position: relative;
        }
        
        .glitch-title {
            font-family: 'Press Start 2P', cursive;
            font-size: 2rem;
            background: linear-gradient(to right, var(--neon-blue), var(--neon-purple));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            position: relative;
            display: inline-block;
            text-shadow: 0 0 10px rgba(0, 178, 255, 0.4);
            letter-spacing: 2px;
            padding-bottom: 10px;
        }
        
        .glitch-title::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(to right, var(--neon-blue), transparent);
        }
        
        /* Matrix raining code animation */
        .matrix-effect {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
            opacity: 0.05;
        }
        
        .subtitle {
            color: var(--text-secondary);
            margin-bottom: 15px;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .subtitle i {
            color: var(--neon-blue);
        }
        
        .card {
            background-color: var(--card-bg);
            border: 1px solid rgba(0, 178, 255, 0.2);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .card::before {
            content: "";
            position: absolute;
            top: 0;
            left: -50%;
            width: 50%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(0, 178, 255, 0.05),
                transparent
            );
            animation: scan 4s linear infinite;
        }
        
        @keyframes scan {
            0% { left: -50%; }
            100% { left: 150%; }
        }
        
        .card-header {
            border-bottom: 1px solid rgba(0, 178, 255, 0.2);
            padding-bottom: 15px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-title {
            color: var(--neon-blue);
            font-size: 1.2rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .card-badge {
            background-color: var(--neon-purple);
            color: white;
            font-size: 0.7rem;
            padding: 3px 8px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .search-form {
            margin-bottom: 25px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .input-group {
            flex: 1;
            min-width: 250px;
            position: relative;
        }
        
        .file-input {
            width: 100%;
            background-color: var(--darker-bg);
            border: 2px solid rgba(0, 178, 255, 0.3);
            color: var(--text-primary);
            padding: 12px 15px 12px 40px;
            border-radius: 6px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            outline: none;
        }
        
        .file-input:focus {
            border-color: var(--neon-blue);
            box-shadow: var(--glow-blue);
        }
        
        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--neon-blue);
            pointer-events: none;
        }
        
        .submit-btn {
            background: linear-gradient(45deg, var(--neon-blue), var(--neon-purple));
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }
        
        .submit-btn:active {
            transform: translateY(1px);
        }
        
        .error-message {
            background-color: rgba(255, 54, 85, 0.1);
            border-left: 4px solid var(--danger);
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .error-message i {
            color: var(--danger);
            font-size: 1.5rem;
        }
        
        .file-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        
        .file-item {
            background-color: rgba(11, 11, 14, 0.5);
            border: 1px solid rgba(0, 178, 255, 0.15);
            border-radius: 6px;
            padding: 15px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .file-item::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, var(--neon-blue), var(--neon-purple));
            opacity: 0.5;
        }
        
        .file-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 178, 255, 0.1);
            border-color: rgba(0, 178, 255, 0.4);
        }
        
        .file-item:hover::before {
            opacity: 1;
        }
        
        .file-icon {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .file-icon i {
            font-size: 1.5rem;
            color: var(--neon-blue);
        }
        
        .file-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-primary);
            margin-bottom: 5px;
        }
        
        .file-path {
            font-size: 0.8rem;
            color: var(--text-secondary);
            word-break: break-all;
            font-family: 'JetBrains Mono', monospace;
        }
        
        .file-link {
            text-decoration: none;
            color: inherit;
            display: block;
            height: 100%;
        }
        
        .file-link:hover .file-name {
            color: var(--hover-blue);
        }
        
        .terminal-box {
            background-color: var(--darker-bg);
            border: 1px solid rgba(0, 255, 102, 0.3);
            border-radius: 6px;
            padding: 20px;
            margin-top: 20px;
            position: relative;
        }
        
        .terminal-header {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            background-color: rgba(0, 255, 102, 0.2);
            padding: 5px 10px;
            font-size: 0.8rem;
            color: var(--terminal-green);
            display: flex;
            justify-content: space-between;
        }
        
        .terminal-title {
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .terminal-controls {
            display: flex;
            gap: 5px;
        }
        
        .terminal-control {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        .terminal-content {
            font-family: 'JetBrains Mono', monospace;
            color: var(--terminal-green);
            font-size: 0.9rem;
            margin-top: 25px;
            white-space: pre-wrap;
            word-break: break-all;
        }
        
        .debug-info {
            font-size: 0.8rem;
            color: var(--text-secondary);
            margin-top: 30px;
            background-color: rgba(255, 255, 255, 0.03);
            padding: 10px;
            border-radius: 6px;
            border-left: 2px solid var(--neon-purple);
        }
        
        .hint-box {
            background-color: rgba(138, 43, 226, 0.1);
            border-left: 4px solid var(--neon-purple);
            padding: 15px;
            border-radius: 6px;
            margin-top: 25px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .hint-box i {
            color: var(--neon-purple);
            font-size: 1.5rem;
        }
        
        .hint-content {
            font-size: 0.9rem;
        }
        
        .hint-title {
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--neon-purple);
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--neon-blue);
            text-decoration: none;
            font-size: 0.9rem;
            margin-top: 20px;
            transition: all 0.2s ease;
        }
        
        .back-link:hover {
            color: var(--hover-blue);
            text-decoration: none;
        }
        
        .vulnerability-indicator {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: rgba(11, 11, 14, 0.95);
            border: 1px solid var(--danger);
            border-radius: 6px;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.8rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .vulnerability-indicator i {
            color: var(--danger);
        }
        
        .vulnerability-pulse {
            width: 10px;
            height: 10px;
            background-color: var(--danger);
            border-radius: 50%;
            position: relative;
        }
        
        .vulnerability-pulse::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-color: var(--danger);
            animation: pulse 2s infinite;
            opacity: 0.5;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(2); opacity: 0; }
            100% { transform: scale(1); opacity: 0.5; }
        }
        
        .typing {
            border-right: 2px solid var(--neon-blue);
            animation: blink 1s step-end infinite;
            white-space: nowrap;
            overflow: hidden;
        }
        
        @keyframes blink {
            from, to { border-color: transparent; }
            50% { border-color: var(--neon-blue); }
        }
        
        @media (max-width: 768px) {
            .search-form {
                flex-direction: column;
            }
            
            .glitch-title {
                font-size: 1.5rem;
            }
            
            .file-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="title-container">
            <h1 class="glitch-title">Super File Viewer</h1>
            <canvas class="matrix-effect" id="matrix-canvas"></canvas>
        </div>
        
        <p class="subtitle"><i class="fas fa-file-code"></i> Explore and view files across the system</p>
        
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-search"></i> File Explorer</div>
                <div class="card-badge">Unrestricted Access</div>
            </div>
            
            <form method="GET" class="search-form">
                <div class="input-group">
                    <i class="fas fa-folder-open input-icon"></i>
                    <input 
                        type="text" 
                        name="file" 
                        class="file-input" 
                        placeholder="e.g., files/sample.txt or /etc/passwd" 
                        value="<?php echo isset($_GET['file']) ? htmlspecialchars($_GET['file']) : ''; ?>"
                        autocomplete="off"
                    >
                </div>
                <button type="submit" class="submit-btn">
                    <i class="fas fa-search"></i> View File
                </button>
            </form>
            
            <?php if (isset($error)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="hint-box">
                <i class="fas fa-lightbulb"></i>
                <div class="hint-content">
                    <div class="hint-title">Path Traversal Hint</div>
                    <p>The basic protection can be bypassed. Try to find the flag using alternative methods.</p>
                </div>
            </div>
            
            <div class="terminal-box">
                <div class="terminal-header">
                    <div class="terminal-title">System Terminal</div>
                    <div class="terminal-controls">
                        <div class="terminal-control"></div>
                        <div class="terminal-control"></div>
                        <div class="terminal-control"></div>
                    </div>
                </div>
                <div class="terminal-content">
                    <span class="typing">root@server:~# ls -la /sensitive_files</span>
                    <br>
                    <span style="color: #b3b3cc;">total 16</span>
                    <br>
                    <span style="color: #b3b3cc;">drwxr-xr-x 2 root root 4096 May 15 2025 .</span>
                    <br>
                    <span style="color: #b3b3cc;">drwxr-xr-x 10 root root 4096 May 15 2025 ..</span>
                    <br>
                    <span style="color: #b3b3cc;">-rw-r--r-- 1 root root 125 May 15 2025 flag.txt</span>
                    <br>
                    <span style="color: #b3b3cc;">-rw-r--r-- 1 root root 843 May 15 2025 config.php</span>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-folder"></i> Example Files</div>
            </div>
            
            <div class="file-list">
                <div class="file-item">
                    <a href="?file=files/sample.txt" class="file-link">
                        <div class="file-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="file-name">Sample Text</div>
                        <div class="file-path">files/sample.txt</div>
                    </a>
                </div>
                
                <div class="file-item">
                    <a href="?file=/etc/passwd" class="file-link">
                        <div class="file-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="file-name">System Users</div>
                        <div class="file-path">/etc/passwd</div>
                    </a>
                </div>
                
                <div class="file-item">
                    <a href="?file=/etc/hosts" class="file-link">
                        <div class="file-icon">
                            <i class="fas fa-network-wired"></i>
                        </div>
                        <div class="file-name">Hosts File</div>
                        <div class="file-path">/etc/hosts</div>
                    </a>
                </div>
                
                <div class="file-item">
                    <a href="?file=/proc/self/environ" class="file-link">
                        <div class="file-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="file-name">Environment Vars</div>
                        <div class="file-path">/proc/self/environ</div>
                    </a>
                </div>
                
                <div class="file-item">
                    <a href="?file=../flag.txt" class="file-link">
                        <div class="file-icon">
                            <i class="fas fa-flag"></i>
                        </div>
                        <div class="file-name">Parent Flag</div>
                        <div class="file-path">../flag.txt</div>
                    </a>
                </div>
                
                <div class="file-item">
                    <a href="?file=/var/log/apache2/access.log" class="file-link">
                        <div class="file-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <div class="file-name">Apache Logs</div>
                        <div class="file-path">/var/log/apache2/access.log</div>
                    </a>
                </div>
            </div>
        </div>
        
        <a href="/" class="back-link">
            <i class="fas fa-chevron-left"></i> Back to Home
        </a>
        
        <div class="debug-info">
            <!-- Debug information -->
            Current directory: <?php echo htmlspecialchars(getcwd()); ?>
        </div>
    </div>
    
    <div class="vulnerability-indicator">
        <div class="vulnerability-pulse"></div>
        <span>Path Traversal Vulnerability Active</span>
        <i class="fas fa-bug"></i>
    </div>
    
    <script>
        // Matrix rain effect
        const canvas = document.getElementById('matrix-canvas');
        const ctx = canvas.getContext('2d');
        
        canvas.width = document.querySelector('.title-container').offsetWidth;
        canvas.height = document.querySelector('.title-container').offsetHeight;
        
        const characters = "01アイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲン";
        const columns = Math.floor(canvas.width / 20);
        const drops = [];
        
        for (let i = 0; i < columns; i++) {
            drops[i] = Math.random() * -100;
        }
        
        function drawMatrix() {
            ctx.fillStyle = 'rgba(11, 11, 14, 0.05)';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            
            ctx.fillStyle = '#00b2ff';
            ctx.font = '15px monospace';
            
            for (let i = 0; i < drops.length; i++) {
                const text = characters.charAt(Math.floor(Math.random() * characters.length));
                ctx.fillText(text, i * 20, drops[i] * 20);
                
                if (drops[i] * 20 > canvas.height && Math.random() > 0.975) {
                    drops[i] = 0;
                }
                
                drops[i]++;
            }
        }
        
        setInterval(drawMatrix, 50);
        
        // Handle window resize
        window.addEventListener('resize', function() {
            canvas.width = document.querySelector('.title-container').offsetWidth;
            canvas.height = document.querySelector('.title-container').offsetHeight;
        });
        
        // Add typing animation to input placeholder
        const placeholders = [
            "files/sample.txt",
            "/etc/passwd",
            "../flag.txt",
            "../../../flag.txt",
            "/var/www/html/flag.txt",
            "....//....//flag.txt"
        ];
        
        let currentPlaceholder = 0;
        const fileInput = document.querySelector('.file-input');
        
        function rotatePlaceholder() {
            fileInput.setAttribute('placeholder', placeholders[currentPlaceholder]);
            currentPlaceholder = (currentPlaceholder + 1) % placeholders.length;
        }
        
        setInterval(rotatePlaceholder, 3000);
    </script>
</body>
</html>