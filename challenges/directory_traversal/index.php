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
<html>
<head>
    <title>Super File Viewer</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 5px; }
        a { color: #0066cc; text-decoration: none; }
        a:hover { text-decoration: underline; }
        form { margin: 20px 0; }
        input[type="text"] { width: 300px; padding: 5px; }
        .error { color: red; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Super File Viewer</h1>
        <p>View any file on the system:</p>
        
        <form method="GET">
            <input type="text" name="file" placeholder="e.g., files/sample.txt or /etc/passwd">
            <input type="submit" value="View File">
        </form>
        
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        
        <h2>Example Files:</h2>
        <ul>
            <li><a href="?file=files/sample.txt">files/sample.txt</a></li>
            <li><a href="?file=/etc/passwd">/etc/passwd</a> (System users)</li>
            <li><a href="?file=/etc/hosts">/etc/hosts</a> (Hosts file)</li>
            <li><a href="?file=/proc/self/environ">/proc/self/environ</a> (Environment variables)</li>
            <li><a href="?file=../flag.txt">../flag.txt</a> (Parent directory flag)</li>
        </ul>
        
        <p><a href="/">Back to Home</a></p>
        
        <!-- Debug: Current directory is <?php echo getcwd(); ?> -->
    </div>
</body>
</html>
