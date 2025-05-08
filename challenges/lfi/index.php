<?php
if (isset($_GET['file'])) {
    $file = $_GET['file'];
    // Remove directory traversal protection to make it more vulnerable
    $file_path = $file;
    
    if (file_exists($file_path)) {
        // Check if it's a directory
        if (is_dir($file_path)) {
            echo "<h2>Directory Listing: $file_path</h2>";
            echo "<ul>";
            foreach (scandir($file_path) as $item) {
                if ($item !== "." && $item !== "..") {
                    echo "<li><a href='?file=" . urlencode("$file_path/$item") . "'>$item</a></li>";
                }
            }
            echo "</ul>";
        } else {
            // Display file contents
            echo "<h2>File: $file_path</h2>";
            echo "<pre>" . htmlspecialchars(file_get_contents($file_path)) . "</pre>";
        }
    } else {
        echo "File not found!";
    }
} else {
    // Default files to show
    $default_files = [
        '/etc/passwd' => 'System Users',
        '/etc/hosts' => 'Hosts File',
        '/home/' => 'Home Directory',
        'secret/flag.txt' => 'Secret Flag'
    ];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Advanced File Viewer</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 5px; }
        a { color: #0066cc; }
    </style>
</head>
<body>
    <h1>Advanced File Viewer</h1>
    <p>View any file on the system:</p>
    
    <form method="GET">
        <input type="text" name="file" placeholder="e.g., /etc/passwd" size="50">
        <input type="submit" value="View File">
    </form>
    
    <?php if (!isset($_GET['file'])): ?>
        <h2>Suggested Files:</h2>
        <ul>
            <?php foreach ($default_files as $path => $desc): ?>
                <li><a href="?file=<?= urlencode($path) ?>"><?= $desc ?></a> - <?= $path ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    
    <p><a href="/">Back to Home</a></p>
</body>
</html>
