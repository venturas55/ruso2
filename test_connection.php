<?php
// Test database connection
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing database connection...<br>";

// Load environment variables
function loadEnv($path) {
    if (!file_exists($path)) {
        echo "Error: .env file not found at $path<br>";
        return false;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Remove quotes if present
            if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
                (substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
                $value = substr($value, 1, -1);
            }
            
            putenv("$key=$value");
            $_ENV[$key] = $value;
        }
    }
    return true;
}

loadEnv(__DIR__ . '/.env');

echo "Environment variables loaded:<br>";
echo "DB_HOST: " . ($_ENV['DB_HOST'] ?? 'NOT SET') . "<br>";
echo "DB_USER: " . ($_ENV['DB_USER'] ?? 'NOT SET') . "<br>";
echo "DB_PASS: " . ($_ENV['DB_PASS'] ?? 'NOT SET') . "<br>";
echo "DB_NAME: " . ($_ENV['DB_NAME'] ?? 'NOT SET') . "<br>";

// Test PDO connection (like funciones.php)
try {
    $db = new PDO("mysql:host=".$_ENV['DB_HOST'].";dbname=".$_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
    $db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, TRUE);
    echo "<br><strong>PDO Connection: SUCCESS</strong><br>";
} catch (PDOException $e) {
    echo "<br><strong>PDO Connection: FAILED</strong><br>";
    echo "Error: " . $e->getMessage() . "<br>";
}

// Test MySQLi connection (like conexion.php)
try {
    $conexion = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
    if ($conexion->connect_error) {
        echo "<br><strong>MySQLi Connection: FAILED</strong><br>";
        echo "Error: " . $conexion->connect_error . "<br>";
    } else {
        echo "<br><strong>MySQLi Connection: SUCCESS</strong><br>";
        $conexion->close();
    }
} catch (Exception $e) {
    echo "<br><strong>MySQLi Connection: FAILED</strong><br>";
    echo "Error: " . $e->getMessage() . "<br>";
}

echo "<br>Test completed.";
?>
