<?php
require_once 'config.php';
require_once 'AESCrypto.php';

// Inisialisasi AES dengan key dari config
$aes = new AESCrypto();
$aes->setKeyFromHex(AES_KEY_HEX);

// Proses form submission
if ($_POST && isset($_POST['query'])) {
    $query = trim($_POST['query']);
    
    if (!empty($query)) {
        try {
            // Enkripsi query
            $encryptedQuery = $aes->encrypt($query);
            
            // URL encode untuk keamanan
            $encodedQuery = urlencode($encryptedQuery);
            
            // Redirect ke halaman hasil
            header("Location: " . BASE_URL . "/search.php?q=" . $encodedQuery);
            exit();
            
        } catch (Exception $e) {
            $error = "Terjadi kesalahan saat enkripsi: " . $e->getMessage();
        }
    } else {
        $error = "Query pencarian tidak boleh kosong!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencarian Database Mahasiswa - Enkripsi AES</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 500px;
        }
        
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }
        
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 2rem;
            font-size: 0.9rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
        }
        
        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .error {
            background: #fee;
            color: #c33;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 1rem;
            border: 1px solid #fcc;
        }
        
        .info {
            background: #e3f2fd;
            color: #1565c0;
            padding: 15px;
            border-radius: 5px;
            margin-top: 2rem;
            font-size: 0.9rem;
            line-height: 1.4;
        }
        
        .info strong {
            display: block;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Pencarian Database Mahasiswa</h1>
        <p class="subtitle">Sistem dengan Enkripsi AES-128-CBC</p>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="query">Cari berdasarkan Nama atau Jurusan:</label>
                <input type="text" 
                       id="query" 
                       name="query" 
                       placeholder="Masukkan nama mahasiswa atau jurusan..."
                       value="<?php echo isset($_POST['query']) ? htmlspecialchars($_POST['query']) : ''; ?>"
                       required>
            </div>
            
            <button type="submit" class="btn">🔒 Cari & Enkripsi</button>
        </form>
        
    </div>
</body>
</html>