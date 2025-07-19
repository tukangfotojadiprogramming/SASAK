<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "sasak_heritage";

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
// Create tables if they don't exist
function initializeDatabase($conn) {
    // Users table
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(30) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(50) NOT NULL UNIQUE,
        full_name VARCHAR(100),
        phone VARCHAR(20),
        role ENUM('admin','user') DEFAULT 'user',
        is_suspended BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if (!$conn->query($sql)) {
        die("Error creating users table: " . $conn->error);
    }
    
    // Insert admin if not exists
    $checkAdmin = $conn->query("SELECT id FROM users WHERE username='admin'");
    if ($checkAdmin->num_rows == 0) {
        $conn->query("INSERT INTO users (username, password, email, full_name, role) 
                      VALUES ('admin', 'admin123', 'admin@sasak.com', 'Administrator', 'admin')");
    }
    
    // Cultural items table
    $sql = "CREATE TABLE IF NOT EXISTS cultural_items (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(100) NOT NULL,
        description TEXT,
        category ENUM('tenun','rumah','musik','bahasa','pakaian','makanan') NOT NULL,
        image_url VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if (!$conn->query($sql)) {
        die("Error creating cultural_items table: " . $conn->error);
    }
    
    // Tourism destinations table
    $sql = "CREATE TABLE IF NOT EXISTS destinations (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        description TEXT,
        location VARCHAR(100),
        latitude DECIMAL(10,8),
        longitude DECIMAL(11,8),
        image_url VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if (!$conn->query($sql)) {
        die("Error creating destinations table: " . $conn->error);
    }
    
    // UMKM products table
    $sql = "CREATE TABLE IF NOT EXISTS products (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        description TEXT,
        price DECIMAL(10,2) NOT NULL,
        category ENUM('tenun','kopi','kuliner','kerajinan') NOT NULL,
        location VARCHAR(100),
        latitude DECIMAL(10,8),
        longitude DECIMAL(11,8),
        image_url VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if (!$conn->query($sql)) {
        die("Error creating products table: " . $conn->error);
    }
}

// Initialize database
initializeDatabase($conn);

// Authentication functions
function authenticateUser($conn, $username, $password) {
    $stmt = $conn->prepare("SELECT id, username, password, role, is_suspended FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Check if account is suspended
        if ($user['is_suspended']) {
            return ['success' => false, 'message' => 'Akun Anda telah ditangguhkan'];
        }
        
        // Verify password (plain text comparison - you should use password_verify() if using hashed passwords)
        if ($password === $user['password']) {
            return ['success' => true, 'user' => $user];
        }
    }
    
    return ['success' => false, 'message' => 'Username atau password salah'];
}
?>