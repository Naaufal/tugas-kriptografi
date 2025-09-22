<?php

$host = 'localhost';
$username = 'root';
$password = 'fall';

try {
    // Koneksi ke MySQL
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Koneksi ke MySQL berhasil!\n";
    
    // Buat database jika belum ada
    $pdo->exec("CREATE DATABASE IF NOT EXISTS kriptografi");
    echo "Database 'kriptografi' berhasil dibuat/sudah ada!\n";
    
    // Pilih database
    $pdo->exec("USE kriptografi");
    
    // Buat tabel mahasiswa
    $createTable = "
    CREATE TABLE IF NOT EXISTS mahasiswa (
        nim VARCHAR(20) PRIMARY KEY,
        nama VARCHAR(100) NOT NULL,
        jurusan VARCHAR(50) NOT NULL,
        email VARCHAR(100) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($createTable);
    echo "Tabel 'mahasiswa' berhasil dibuat!\n";
    
    echo "Setup database selesai!\n";
    echo "Silakan tambahkan data mahasiswa melalui:\n";
    echo "1. Import CSV: php import.php\n";
    echo "2. SQL file: mysql -u root -p kriptografi < data.sql\n";
    echo "3. Web interface: http://localhost:3000/import.php\n";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>