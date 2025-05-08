<?php
if (isset($_GET['ip'])) {
    $ip = $_GET['ip'];
    $output = shell_exec("ping -c 4 " . $ip);
    $lines = explode("\n", $output);
    $formatted_output = "";
    
    // Format the output with delayed animation
    foreach($lines as $index => $line) {
        if(trim($line) != "") {
            $delay = ($index * 0.1) + 0.3;
            $formatted_output .= "<div class='terminal-line text-light' style='animation-delay: {$delay}s'>" . htmlspecialchars($line) . "</div>";
        }
    }
}

$flag = file_get_contents('flag.txt');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Network Diagnostic Tool</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts - Roboto Mono & Iceland -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;500;600&family=Iceland&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --secondary: #06b6d4;
            --accent: #f43f5e;
            --dark: #111827;
            --darker: #000000;
            --light: #f9fafb;
            --gray: #9ca3af;
            --card-bg: #0a0a0a;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Roboto Mono', monospace;
            background-color: #000000;
            color: var(--light);
            line-height: 1.6;
            min-height: 100vh;
            position: relative;
            background-image: 
                linear-gradient(rgba(79, 70, 229, 0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(79, 70, 229, 0.1) 1px, transparent 1px);
            background-size: 30px 30px;
        }
        
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 20% 25%, rgba(79, 70, 229, 0.05) 0%, transparent 40%), 
                radial-gradient(circle at 80% 75%, rgba(6, 182, 212, 0.05) 0%, transparent 40%);
            z-index: -1;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        .header {
            margin-bottom: 30px;
            text-align: center;
        }
        
        .title {
            font-family: 'Iceland', cursive;
            font-size: 3rem;
            font-weight: bold;
            background: linear-gradient(to right, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 10px;
            letter-spacing: 1px;
            text-shadow: 0 0 20px rgba(79, 70, 229, 0.3);
        }
        
        .subtitle {
            color: var(--gray);
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .card {
            background-color: var(--card-bg);
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(79, 70, 229, 0.2);
        }
        
        .card-header {
            background-color: rgba(79, 70, 229, 0.05);
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(79, 70, 229, 0.1);
        }
        
        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--light);
        }
        
        .card-title i {
            color: var(--secondary);
        }
        
        .card-badge {
            background-color: var(--accent);
            color: white;
            font-size: 0.7rem;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .card-body {
            padding: 25px;
        }
        
        .search-form {
            margin-bottom: 20px;
        }
        
        .input-group {
            position: relative;
            display: flex;
        }
        
        .input-group-text {
            background-color: var(--primary) !important;
            color: white !important;
            border: none !important;
            padding: 0 15px !important;
        }
        
        .form-control {
            background-color: #0a0a0a !important;
            border: 1px solid rgba(79, 70, 229, 0.2) !important;
            color: var(--light) !important;
            padding: 12px !important;
            border-radius: 0 6px 6px 0 !important;
            font-family: 'Roboto Mono', monospace !important;
            transition: all 0.3s ease !important;
        }
        
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.3) !important;
            border-color: var(--primary) !important;
        }
        
        .form-control::placeholder {
            color: var(--gray) !important;
        }
        
        .btn-primary {
            background-color: var(--primary) !important;
            border-color: var(--primary) !important;
            border-radius: 6px !important;
            padding: 10px 20px !important;
            font-family: 'Roboto Mono', monospace !important;
            transition: all 0.3s ease !important;
            font-weight: 500 !important;
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 5px 15px rgba(79, 70, 229, 0.3) !important;
        }
        
        .info-box {
            background-color: rgba(6, 182, 212, 0.05);
            border-left: 4px solid var(--secondary);
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }
        
        .info-box i {
            color: var(--secondary);
            font-size: 1.5rem;
            margin-top: 3px;
        }
        
        .tech-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }
        
        .tech-badge {
            background-color: #0a0a0a;
            border: 1px solid rgba(79, 70, 229, 0.2);
            color: var(--light);
            font-size: 0.8rem;
            padding: 5px 12px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        
        .tech-badge:hover {
            background-color: rgba(79, 70, 229, 0.1);
            border-color: var(--primary);
            transform: translateY(-2px);
        }
        
        .tech-badge i {
            color: var(--secondary);
        }
        
        /* Terminal styling */
        .terminal {
            background-color: #000000;
            border: 1px solid rgba(79, 70, 229, 0.3);
            border-radius: 8px;
            overflow: hidden;
            margin-top: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .terminal-header {
            background-color: rgba(79, 70, 229, 0.1);
            padding: 10px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(79, 70, 229, 0.2);
        }
        
        .terminal-title {
            font-size: 0.8rem;
            color: var(--gray);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .terminal-controls {
            display: flex;
            gap: 8px;
        }
        
        .terminal-control {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }
        
        .control-close {
            background-color: var(--danger);
        }
        
        .control-minimize {
            background-color: var(--warning);
        }
        
        .control-maximize {
            background-color: var(--success);
        }
        
        .terminal-body {
            padding: 15px;
            max-height: 350px;
            overflow-y: auto;
            font-family: 'Roboto Mono', monospace;
            font-size: 0.85rem;
        }
        
        .terminal-command {
            color: var(--light);
            display: flex;
            margin-bottom: 10px;
        }
        
        .terminal-prompt {
            color: var(--success);
            margin-right: 10px;
            user-select: none;
        }
        
        .terminal-line {
            opacity: 0;
            animation: fade-in 0.3s ease forwards;
            margin-bottom: 3px;
            line-height: 1.4;
            display: flex;
        }
        
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .hint-box {
            background-color: rgba(244, 63, 94, 0.05);
            border-left: 4px solid var(--accent);
            padding: 15px;
            border-radius: 6px;
            margin-top: 20px;
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }
        
        .hint-box i {
            color: var(--accent);
            font-size: 1.5rem;
            margin-top: 3px;
        }
        
        .vulnerability-indicator {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: rgba(0, 0, 0, 0.7);
            border: 1px solid var(--accent);
            border-radius: 30px;
            padding: 8px 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.8rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }
        
        .vulnerability-dot {
            width: 10px;
            height: 10px;
            background-color: var(--accent);
            border-radius: 50%;
            position: relative;
        }
        
        .vulnerability-dot::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: var(--accent);
            border-radius: 50%;
            animation: pulse 2s infinite;
            opacity: 0.7;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.7; }
            50% { transform: scale(2.5); opacity: 0; }
            100% { transform: scale(1); opacity: 0.7; }
        }
        
        .footer {
            text-align: center;
            margin-top: 50px;
            color: var(--gray);
            font-size: 0.9rem;
        }
        
        .system-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        
        .stat-item {
            background-color: #0a0a0a;
            border: 1px solid rgba(79, 70, 229, 0.2);
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .stat-item:hover {
            background-color: #111111;
            transform: translateY(-3px);
            border-color: rgba(79, 70, 229, 0.4);
        }
        
        .stat-icon {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        
        .stat-value {
            font-size: 0.9rem;
            color: var(--light);
            font-weight: 600;
        }
        
        .stat-label {
            font-size: 0.8rem;
            color: var(--gray);
            margin-top: 5px;
        }
        
        .stat-item:nth-child(1) .stat-icon {
            color: var(--success);
        }
        
        .stat-item:nth-child(2) .stat-icon {
            color: var(--warning);
        }
        
        .stat-item:nth-child(3) .stat-icon {
            color: var(--accent);
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            
            .title {
                font-size: 2.2rem;
            }
            
            .system-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="title">Network Diagnostic Tool</h1>
            <p class="subtitle">Execute network commands to diagnose connectivity issues and test server response times</p>
        </div>
        
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-terminal"></i> Command Console
                </div>
                <div class="card-badge">Vulnerable</div>
            </div>
            
            <div class="card-body">
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <p class="text-light">Enter an IP address or hostname to test connectivity. This tool executes the ping command directly on our server.</p>
                    </div>
                </div>
                
                <form action="" method="GET" class="search-form">
                    <div class="input-group mb-3">
                        <span class="input-group-text">
                            <i class="fas fa-network-wired"></i>
                        </span>
                        <input type="text" class="form-control" name="ip" placeholder="e.g., 8.8.8.8 or try command injection" value="<?php echo isset($_GET['ip']) ? htmlspecialchars($_GET['ip']) : ''; ?>">
                        <button class="btn btn-primary ms-2" type="submit">
                            <i class="fas fa-paper-plane"></i> Execute
                        </button>
                    </div>
                    
                    <div class="tech-badges">
                        <div class="tech-badge">
                            <i class="fas fa-network-wired"></i> IPv4
                        </div>
                        <div class="tech-badge">
                            <i class="fas fa-exchange-alt"></i> TCP/IP
                        </div>
                        <div class="tech-badge">
                            <i class="fas fa-code"></i> Command Injection
                        </div>
                        <div class="tech-badge">
                            <i class="fas fa-flag"></i> CTF Challenge
                        </div>
                    </div>
                </form>
                
                <div class="hint-box">
                    <i class="fas fa-lightbulb"></i>
                    <div>
                        <p class="text-light"><strong>Hint:</strong> The ping command is executed without proper sanitization. Try to chain additional commands using operators like <code>;</code>, <code>&&</code>, or <code>||</code> to read sensitive files.</p>
                    </div>
                </div>
                
                <?php if(isset($_GET['ip'])): ?>
                <div class="terminal">
                    <div class="terminal-header">
                        <div class="terminal-title">Command Output</div>
                        <div class="terminal-controls">
                            <div class="terminal-control control-close"></div>
                            <div class="terminal-control control-minimize"></div>
                            <div class="terminal-control control-maximize"></div>
                        </div>
                    </div>
                    <div class="terminal-body">
                        <div class="terminal-command">
                            <span class="terminal-prompt">$</span>
                            <span>ping -c 4 <?php echo htmlspecialchars($_GET['ip']); ?></span>
                        </div>
                        <?php echo $formatted_output; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="system-stats">
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="stat-value">Vulnerable</div>
                        <div class="stat-label">Security Status</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-server"></i>
                        </div>
                        <div class="stat-value">Debug Mode</div>
                        <div class="stat-label">Server Mode</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-flag"></i>
                        </div>
                        <div class="stat-value">flag.txt</div>
                        <div class="stat-label">Target File</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>© 2025 Command Injection Lab • <span class="text-danger">For Educational Purposes Only</span></p>
        </div>
    </div>
    
    <div class="vulnerability-indicator">
        <div class="vulnerability-dot"></div>
        <span>Command Injection Vulnerability Active</span>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Add typing animation to terminal prompt cursor
        document.addEventListener('DOMContentLoaded', function() {
            const terminalBody = document.querySelector('.terminal-body');
            if (terminalBody) {
                terminalBody.scrollTop = terminalBody.scrollHeight;
            }
            
            // Rotate through different placeholders
            const inputField = document.querySelector('input[name="ip"]');
            if (inputField) {
                const placeholders = [
                    "8.8.8.8",
                    "127.0.0.1",
                    "8.8.8.8; cat /etc/passwd",
                    "8.8.8.8 && cat flag.txt",
                    "8.8.8.8 | grep flag.txt",
                    "; ls -la"
                ];
                let currentIndex = 0;
                
                function rotatePlaceholder() {
                    inputField.setAttribute('placeholder', placeholders[currentIndex]);
                    currentIndex = (currentIndex + 1) % placeholders.length;
                }
                
                setInterval(rotatePlaceholder, 3000);
            }
        });
    </script>
</body>
</html>