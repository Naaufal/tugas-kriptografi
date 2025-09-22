<?php
require_once 'config.php';
require_once 'AESCrypto.php';

// Inisialisasi AES dengan key dari config
$aes = new AESCrypto();
$aes->setKeyFromHex(AES_KEY_HEX);

$results = [];
$originalQuery = '';
$error = '';
$encryptedParam = '';

// Proses parameter pencarian
if (isset($_GET['q']) && !empty($_GET['q'])) {
    try {
        $encryptedParam = $_GET['q'];
        
        // URL decode
        $encryptedQuery = urldecode($encryptedParam);
        
        // Dekripsi query
        $originalQuery = $aes->decrypt($encryptedQuery);
        
        // Koneksi database
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Query pencarian
        $sql = "SELECT nim, nama, jurusan, email FROM mahasiswa 
                WHERE nama LIKE :query OR jurusan LIKE :query 
                ORDER BY nama ASC";
        
        $stmt = $pdo->prepare($sql);
        $searchParam = '%' . $originalQuery . '%';
        $stmt->bindParam(':query', $searchParam, PDO::PARAM_STR);
        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
} else {
    $error = "Parameter pencarian tidak ditemukan!";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pencarian - Database Mahasiswa</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .header h1 {
            margin-bottom: 0.5rem;
        }
        
        .content {
            padding: 2rem;
        }
        
        .search-info {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 2rem;
            border-left: 4px solid #667eea;
        }
        
        .search-info strong {
            color: #333;
        }
        
        .encrypted-url {
            background: #e9ecef;
            padding: 10px;
            border-radius: 3px;
            font-family: monospace;
            font-size: 0.9rem;
            word-break: break-all;
            margin-top: 0.5rem;
        }
        
        .results-count {
            margin-bottom: 1.5rem;
            color: #666;
            font-weight: 500;
        }
        
        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
            background: white;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .results-table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }
        
        .results-table td {
            padding: 1rem;
            border-bottom: 1px solid #eee;
        }
        
        .results-table tr:hover {
            background: #f8f9fa;
        }
        
        .no-results {
            text-align: center;
            padding: 3rem;
            color: #666;
            background: #f8f9fa;
            border-radius: 5px;
        }
        
        .no-results .icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: transform 0.2s;
            margin-bottom: 2rem;
        }
        
        .back-btn:hover {
            transform: translateY(-2px);
        }
        
        .error {
            background: #fee;
            color: #c33;
            padding: 1rem;
            border-radius: 5px;
            border: 1px solid #fcc;
            margin-bottom: 2rem;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            background: #667eea;
            color: white;
            border-radius: 3px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .encryption-details {
            background: #e3f2fd;
            padding: 1rem;
            border-radius: 5px;
            margin-top: 2rem;
            font-size: 0.9rem;
        }
        
        .encryption-details h4 {
            color: #1565c0;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔍 Hasil Pencarian Database Mahasiswa</h1>
            <p>Sistem dengan Enkripsi AES-128-CBC</p>
        </div>
        
        <div class="content">
            <a href="<?php echo BASE_URL; ?>" class="back-btn">← Kembali ke Pencarian</a>
            
            <?php if ($error): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php else: ?>
                
                <div class="results-count">
                    <span class="badge"><?php echo count($results); ?></span> 
                    hasil ditemukan untuk pencarian "<?php echo htmlspecialchars($originalQuery); ?>"
                </div>
                
                <?php if (count($results) > 0): ?>
                    <table class="results-table">
                        <thead>
                            <tr>
                                <th>NIM</th>
                                <th>Nama Mahasiswa</th>
                                <th>Jurusan</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($results as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['nim']); ?></td>
                                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                <td><?php echo htmlspecialchars($row['jurusan']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-results">
                        <div class="icon">🔍</div>
                        <h3>Tidak ada hasil ditemukan</h3>
                        <p>Tidak ada data mahasiswa yang cocok dengan pencarian "<?php echo htmlspecialchars($originalQuery); ?>"</p>
                        <p>Coba gunakan kata kunci yang berbeda.</p>
                    </div>
                <?php endif; ?>
                
                <div class="encryption-details">
                    <h4>🔒 Detail Enkripsi:</h4>
                    <strong>Algoritma:</strong> AES-128-CBC<br>
                    <strong>Mode:</strong> Cipher Block Chaining<br>
                    <strong>Key Length:</strong> 128 bit<br>
                    <strong>IV:</strong> Random 16 bytes per request<br>
                    <strong>Encoding:</strong> Base64 + URL Encoding
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>