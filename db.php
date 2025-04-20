<?php
require_once __DIR__ . '/vendor/autoload.php';

// Only load .env file if it exists 
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

$host =$_ENV['DB_HOST'];
$dbname =$_ENV['DB_NAME'];
$user =$_ENV['DB_USER'];
$password =$_ENV['DB_PASSWORD'] ;

$dsn = "pgsql:host=$host;dbname=$dbname;sslmode=require";  

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

