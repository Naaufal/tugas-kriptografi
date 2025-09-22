# 🔐 Sistem Pencarian Database Mahasiswa dengan Enkripsi AES-128-CBC

Tugas Mata Kuliah Kriptografi - Implementasi Enkripsi AES pada Sistem Pencarian Database

## 📋 Informasi Tugas

- **Mata Kuliah**: Kriptografi
- **Dosen**: Muhammad Najamuddin Dwi Miharja, S.Kom, M.Kom
- **Universitas**: Universitas Pelita Bangsa
- **Kelas**: TI.23.A1

## 👥 Anggota Kelompok

| No | Nama | NIM |
|----|------|-----|
| 1 | Adam Aliyazid | 312310180 |
| 2 | Muhammad Alghofiqi | 312310207 |
| 3 | Muhammad Naufal Ali Akbar | 312310687 |

## 🎯 Tujuan Tugas

Mengimplementasikan enkripsi **AES-128-CBC** pada sistem pencarian database untuk:
- Mempelajari konsep kriptografi simetris
- Implementasi algoritma AES (Advanced Encryption Standard)
- Menerapkan keamanan pada parameter URL
- Memahami penggunaan IV (Initialization Vector) dan padding

## 🔧 Teknologi yang Digunakan

- **Backend**: PHP 7.4+
- **Database**: MariaDB/MySQL
- **Enkripsi**: AES-128-CBC dengan OpenSSL
- **Web Server**: Apache/Nginx
- **Environment**: Linux

## 📁 Struktur Proyek

```
tugas-kriptografi/
├── AESCrypto.php          # Class enkripsi/dekripsi AES
├── config.php             # Konfigurasi database dan key
├── index.php              # Halaman form pencarian
├── search.php             # Halaman hasil pencarian
├── setup.php              # Setup database dan tabel
└── README.md              # Dokumentasi proyek
```

## 🚀 Cara Instalasi

### 1. Clone/Download Repository
```bash
git clone https://github.com/naaufal/tugas-kriptografi.git
cd tugas-kriptografi
```

### 2. Setup Database
```bash
# Jalankan script setup database
php setup.php
```

### 3. Konfigurasi Database
Edit file `config.php` sesuai dengan pengaturan database Anda:
```php
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'kriptografi');
```

### 5. Import Data Mahasiswa
Tambahkan data mahasiswa ke tabel `mahasiswa` dengan struktur:
- `nim` (VARCHAR 20) - Primary Key
- `nama` (VARCHAR 100) - Nama mahasiswa  
- `jurusan` (VARCHAR 50) - Program studi
- `email` (VARCHAR 100) - Email mahasiswa

### 6. Jalankan Aplikasi
```bash
# Menggunakan PHP built-in server
php -S localhost:3000

# Atau gunakan Apache/Nginx
```

## 📖 Cara Penggunaan

### 1. Halaman Pencarian (`index.php`)
<img width="1772" height="1014" alt="image" src="https://github.com/user-attachments/assets/36452dd4-a04d-4607-a977-cdb3f2778e39" />



- User memasukkan kata kunci pencarian (nama atau jurusan)
- Query akan dienkripsi menggunakan AES-128-CBC
- User diarahkan ke halaman hasil dengan parameter terenkripsi

### 2. Halaman Hasil (`search.php`)
<img width="1772" height="1014" alt="image" src="https://github.com/user-attachments/assets/7926c855-60fd-47db-ac74-9ff92a2981c2" />


- Parameter URL berisi query terenkripsi
- Query didekripsi menggunakan key yang sama
- Menampilkan hasil pencarian dalam bentuk tabel

## 🔐 Implementasi Enkripsi

### Algoritma: AES-128-CBC

**Karakteristik:**
- **Key Size**: 128 bit (16 bytes)
- **Block Size**: 128 bit (16 bytes)  
- **Mode**: CBC (Cipher Block Chaining)
- **Padding**: PKCS#7
- **IV**: Random 16 bytes per enkripsi

### Proses Enkripsi
```php
// 1. Generate random IV
$iv = random_bytes(16);

// 2. Encrypt plaintext
$encrypted = openssl_encrypt($plaintext, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);

// 3. Combine IV + encrypted data
$result = base64_encode($iv . $encrypted);
```

### Proses Dekripsi
```php
// 1. Decode from base64
$data = base64_decode($ciphertext);

// 2. Extract IV and encrypted data
$iv = substr($data, 0, 16);
$encrypted = substr($data, 16);

// 3. Decrypt
$decrypted = openssl_decrypt($encrypted, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
```

## 📊 Contoh Pengujian

### Input:
- **Plaintext**: "Teknik Informatika"
- **Key**: "2f4a8b9c1d6e3f7a0b8c4d9e2f5a8b1c"

### Output:
- **IV**: Random 16 bytes (berbeda setiap enkripsi)
- **Ciphertext**: Base64 encoded string
- **URL**: `search.php?q=<encrypted_base64_string>`

## 🧪 Testing

### Manual Testing:
1. **Test Enkripsi**: Input "Informatika" → Ciphertext berbeda setiap kali
2. **Test Dekripsi**: Ciphertext → "Informatika" (konsisten)
3. **Test Pencarian**: Query "Teknik" → Menampilkan mahasiswa TI
4. **Test Error**: Invalid ciphertext → Error message

### Expected Results:
- ✅ Query berhasil dienkripsi
- ✅ URL menampilkan parameter terenkripsi
- ✅ Dekripsi berhasil restore query asli
- ✅ Hasil pencarian sesuai dengan query

## 📝 Kesimpulan

Proyek ini berhasil mengimplementasikan:

1. **Enkripsi AES-128-CBC** untuk mengamankan parameter pencarian
2. **Random IV** untuk memastikan ciphertext berbeda setiap enkripsi
3. **Database integration** dengan PHP dan MariaDB
4. **User-friendly interface** untuk demonstrasi enkripsi
5. **Error handling** yang proper untuk edge cases

Implementasi ini mendemonstrasikan konsep dasar kriptografi simetris dan penggunaannya dalam aplikasi web untuk mengamankan data yang dikirim melalui URL.


*Tugas Kriptografi - Universitas Pelita Bangsa*
