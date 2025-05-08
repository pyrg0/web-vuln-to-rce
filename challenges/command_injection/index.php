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
            $formatted_output .= "<div class='terminal-line' style='animation-delay: {$delay}s'>" . htmlspecialchars($line) . "</div>";
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
    
    <style>
        :root {
            --black: #000000;
            --dark-gray: #222222;
            --medium-gray: #555555;
            --light-gray: #CCCCCC;
            --white: #FFFFFF;
            --off-white: #F5F5F5;
        }
        
        body {
            background-color: var(--black);
            font-family: 'Courier New', monospace;
            overflow-x: hidden;
            color: var(--white);
        }
        
        .neo-container {
            background-color: var(--dark-gray);
            border: 4px solid var(--white);
            border-radius: 2px;
            box-shadow: 10px 10px 0 rgba(255,255,255,0.2);
            transition: all 0.2s ease;
            margin-top: 40px;
            position: relative;
            overflow: hidden;
        }
        
        .neo-container:hover {
            transform: translate(-5px, -5px);
            box-shadow: 15px 15px 0 rgba(255,255,255,0.25);
        }
        
        .neo-header {
            background-color: var(--black);
            color: var(--white);
            padding: 20px;
            font-weight: 800;
            text-transform: uppercase;
            border-bottom: 4px solid var(--white);
            position: relative;
        }
        
        .neo-header::after {
            content: "";
            position: absolute;
            top: 15px;
            right: 20px;
            width: 60px;
            height: 60px;
            background-color: var(--white);
            border-radius: 50%;
            z-index: 0;
            mix-blend-mode: exclusion;
        }
        
        .neo-header h1 {
            font-size: 2.5rem;
            position: relative;
            z-index: 1;
            font-weight: 900;
            letter-spacing: -1px;
        }
        
        .neo-form {
            padding: 30px;
            position: relative;
        }
        
        .form-control {
            border: 3px solid var(--white);
            border-radius: 0;
            padding: 15px;
            font-size: 1.2rem;
            background-color: var(--black);
            margin-bottom: 20px;
            transition: all 0.2s ease;
            color: var(--white);
        }
        
        .form-control:focus {
            box-shadow: 8px 8px 0 rgba(255,255,255,0.3);
            transform: translate(-4px, -4px);
            border-color: var(--white);
            background-color: var(--black);
            color: var(--white);
        }
        
        .btn-neo {
            background-color: var(--white);
            color: var(--black);
            border: 3px solid var(--white);
            border-radius: 0;
            padding: 10px 30px;
            font-size: 1.2rem;
            font-weight: 700;
            text-transform: uppercase;
            box-shadow: 6px 6px 0 rgba(255,255,255,0.2);
            transition: all 0.2s ease;
            position: relative;
            z-index: 1;
        }
        
        .btn-neo:hover {
            transform: translate(-3px, -3px);
            box-shadow: 9px 9px 0 rgba(255,255,255,0.25);
        }
        
        .btn-neo:active {
            transform: translate(3px, 3px);
            box-shadow: 3px 3px 0 rgba(255,255,255,0.15);
        }
        
        .shadow-box {
            border: 3px solid var(--white);
            box-shadow: 6px 6px 0 rgba(255,255,255,0.2);
        }
        
        /* Terminal UI for results - Directly below search */
        .terminal-ui {
            background-color: var(--black);
            color: var(--white);
            font-family: 'Courier New', monospace;
            border: 3px solid var(--white);
            box-shadow: 8px 8px 0 rgba(255,255,255,0.2);
            padding: 20px;
            margin-top: 30px;
            position: relative;
            font-size: 14px;
            max-height: 400px;
            overflow-y: auto;
        }
        
        .terminal-header {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            background-color: var(--white);
            color: var(--black);
            padding: 5px 15px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            text-transform: uppercase;
            font-size: 14px;
        }
        
        .terminal-buttons {
            display: flex;
            gap: 8px;
        }
        
        .terminal-button {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: var(--medium-gray);
        }
        
        .terminal-content {
            margin-top: 30px;
            overflow-x: auto;
        }
        
        .terminal-line {
            opacity: 0;
            animation: type-in 0.3s forwards;
            position: relative;
            padding-left: 15px;
            line-height: 1.5;
            word-break: break-all;
        }
        
        .terminal-line::before {
            content: ">";
            position: absolute;
            left: 0;
            color: var(--light-gray);
        }
        
        .terminal-cursor {
            display: inline-block;
            width: 8px;
            height: 16px;
            background-color: var(--white);
            animation: blink 1s step-end infinite;
            vertical-align: middle;
            margin-left: 5px;
        }
        
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }
        
        @keyframes type-in {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Command injection hint */
        .hint-text {
            color: var(--light-gray);
            font-size: 0.8rem;
            margin-top: 10px;
            text-align: right;
            font-style: italic;
        }
        
        /* Network node styling */
        .network-node {
            position: absolute;
            width: 30px;
            height: 30px;
            background-color: var(--black);
            border: 2px solid var(--white);
            animation: float 3s infinite ease-in-out alternate;
            z-index: 0;
            opacity: 0.5;
        }
        
        .node-1 {
            top: 20%;
            right: 10%;
            clip-path: polygon(50% 0%, 0% 100%, 100% 100%);
            animation-delay: 0.5s;
        }
        
        .node-2 {
            bottom: 15%;
            right: 25%;
            border-radius: 50%;
            animation-delay: 1s;
        }
        
        .node-3 {
            top: 40%;
            left: 5%;
            clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%);
            animation-delay: 1.5s;
        }
        
        .node-4 {
            bottom: 30%;
            left: 15%;
            clip-path: polygon(20% 0%, 80% 0%, 100% 20%, 100% 80%, 80% 100%, 20% 100%, 0% 80%, 0% 20%);
            animation-delay: 2s;
        }
        
        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-10px) rotate(5deg);
            }
            100% {
                transform: translateY(10px) rotate(-5deg);
            }
        }
        
        .badge-neo {
            background-color: var(--black);
            color: var(--white);
            border: 2px solid var(--white);
            padding: 0.4rem 0.6rem;
            font-weight: normal;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.7rem;
        }
        
        .footer {
            margin-top: 50px;
            padding: 20px;
            border-top: 1px solid var(--white);
            text-align: center;
            opacity: 0.7;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="neo-container">
            <div class="neo-header">
                <h1><i class="fas fa-terminal me-3"></i>Network Diagnostic Tool</h1>
                <p class="lead mb-0">Execute network commands to diagnose connectivity issues</p>
            </div>
            
            <div class="neo-form">
                <div class="network-node node-1"></div>
                <div class="network-node node-2"></div>
                <div class="network-node node-3"></div>
                <div class="network-node node-4"></div>
                
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <p class="fs-5 mb-4" style="border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 10px;">
                            Enter an IP address or hostname to ping:
                        </p>
                        
                        <form method="GET" class="mb-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="ip" placeholder="e.g., 8.8.8.8 or try special commands" value="<?php echo isset($_GET['ip']) ? htmlspecialchars($_GET['ip']) : ''; ?>" required>
                                <button type="submit" class="btn-neo">
                                    <i class="fas fa-bolt me-2"></i>Execute
                                </button>
                            </div>
                            
                            <div class="hint-text">
                                <!-- Command injection hint -->
                                Try to find a way to read the flag.txt file...
                            </div>
                        </form>
                        
                        <!-- Terminal appears directly below the form -->
                        <?php if(isset($_GET['ip'])): ?>
                        <div class="terminal-ui">
                            <div class="terminal-header">
                                <div>COMMAND OUTPUT</div>
                                <div class="terminal-buttons">
                                    <div class="terminal-button"></div>
                                    <div class="terminal-button"></div>
                                    <div class="terminal-button"></div>
                                </div>
                            </div>
                            <div class="terminal-content">
                                <div class="terminal-line" style="animation-delay: 0.1s; color: var(--light-gray);">
                                    Executing: ping -c 4 <?php echo htmlspecialchars($_GET['ip']); ?>
                                </div>
                                <div class="terminal-line" style="animation-delay: 0.2s; color: var(--light-gray);">
                                    -----------------------------------
                                </div>
                                <?php echo $formatted_output; ?>
                                <div class="terminal-cursor"></div>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="alert shadow-box" style="background-color: var(--dark-gray); color: var(--white); border: 1px solid var(--white);">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle fs-3 me-3"></i>
                                <div>
                                    <h5 class="mb-1">Network Diagnostics Tool</h5>
                                    <p class="mb-0">Enter an IP address or hostname above to test connectivity. This tool runs system commands to verify network status.</p>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <div class="row">
                <div class="col-md-4">
                    <i class="fas fa-lock-open fs-4 mb-2"></i>
                    <p>Security: Vulnerable</p>
                </div>
                <div class="col-md-4">
                    <i class="fas fa-server fs-4 mb-2"></i>
                    <p>Server Mode: Debug</p>
                </div>
                <div class="col-md-4">
                    <i class="fas fa-flag fs-4 mb-2"></i>
                    <p>Flag Status: Hidden</p>
                </div>
            </div>
            <p class="mt-3">© 2025 Command Injection Lab • <i class="fas fa-code"></i> with <i class="fas fa-bug"></i></p>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>