<?php
echo "<h2>PHP Extensions Check</h2>";

echo "<h3>Available Extensions:</h3>";
$extensions = get_loaded_extensions();
foreach ($extensions as $ext) {
    echo "- $ext<br>";
}

echo "<h3>Database Extensions:</h3>";
if (extension_loaded('pdo')) {
    echo "PDO: <span style='color: green;'>LOADED</span><br>";
} else {
    echo "PDO: <span style='color: red;'>NOT LOADED</span><br>";
}

if (extension_loaded('pdo_mysql')) {
    echo "PDO MySQL: <span style='color: green;'>LOADED</span><br>";
} else {
    echo "PDO MySQL: <span style='color: red;'>NOT LOADED</span><br>";
}

if (extension_loaded('mysqli')) {
    echo "MySQLi: <span style='color: green;'>LOADED</span><br>";
} else {
    echo "MySQLi: <span style='color: red;'>NOT LOADED</span><br>";
}

echo "<h3>PHP Version:</h3>";
echo phpversion();

echo "<h3>Loaded Configuration File:</h3>";
echo php_ini_loaded_file();
?>
