<?php
if (isset($_GET['ip'])) {
    $ip = $_GET['ip'];
    $output = shell_exec("ping -c 4 " . $ip);
    echo "<pre>$output</pre>";
}

$flag = file_get_contents('flag.txt');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Network Diagnostic Tool</title>
</head>
<body>
    <h1>Network Diagnostic Tool</h1>
    <p>Enter an IP address to ping:</p>
    <form method="GET">
        <input type="text" name="ip" placeholder="e.g., 8.8.8.8">
        <input type="submit" value="Ping">
    </form>
    <!-- Try to find a way to read flag.txt -->
</body>
</html>
