<?php
// Original config.php code

// $server_name = 'localhost';
// $username = 'root'; // Default XAAMP username
// $password = ""; // Default XAAMP password
// $dbname = 'loruki'; 

// $conn = new mysqli($server_name, $username, $password, $dbname);

// if($conn->connect_error){
//   die("Connection failed: " . $conn->connect_error);
// }

//Updated config.php code, supports any database connection

$db_type = 'mysql'; // Change to 'pgsql' for PostgreSQL, 'sqlite' for SQLite
$host = 'localhost';
$dbname = 'loruki';
$username = 'root';
$password = '';

// Handle SQLite (No Host, No Username/Password)
if ($db_type === 'sqlite') {
    $dsn = "sqlite:" . __DIR__ . "/database.sqlite"; // Adjust SQLite file path
} else {
    $dsn = "$db_type:host=$host;dbname=$dbname;charset=utf8";
}

try {
    // Create a PDO Connection
    $conn = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Error handling
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch associative arrays
    ]);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>