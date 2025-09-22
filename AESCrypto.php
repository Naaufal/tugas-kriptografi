<?php


class AESCrypto {
    private $key;
    private $method;
    
    public function __construct($key = null) {
        $this->method = 'AES-128-CBC';
        
        // Jika tidak ada key yang diberikan, generate random key
        $this->key = $key ?: $this->generateKey();
    }
    
    /**
     * Generate random 128-bit key
     */
    private function generateKey() {
        return random_bytes(16); // 16 bytes = 128 bits
    }
    
    /**
     * Get key dalam format hex untuk disimpan/debugging
     */
    public function getKeyHex() {
        return bin2hex($this->key);
    }
    
    /**
     * Set key dari hex string
     */
    public function setKeyFromHex($hexKey) {
        $this->key = hex2bin($hexKey);
    }
    
    /**
     * Enkripsi plaintext
     */
    public function encrypt($plaintext) {
        // Generate random IV
        $iv = random_bytes(16);
        
        // Enkripsi data
        $encrypted = openssl_encrypt($plaintext, $this->method, $this->key, OPENSSL_RAW_DATA, $iv);
        
        if ($encrypted === false) {
            throw new Exception('Enkripsi gagal');
        }
        

        $result = base64_encode($iv . $encrypted);
        
        return $result;
    }
    
    /**
     * Dekripsi ciphertext
     */
    public function decrypt($ciphertext) {
        try {
            // Decode dari base64
            $data = base64_decode($ciphertext, true); // strict mode
            
            if ($data === false) {
                throw new Exception('Invalid base64 data');
            }
            
            // Check minimum length (IV + at least 1 block of encrypted data)
            if (strlen($data) < 32) { // 16 bytes IV + 16 bytes minimum encrypted data
                throw new Exception('Data too short - possible corruption');
            }
            
            // Pisahkan IV (16 bytes pertama) dan encrypted data
            $iv = substr($data, 0, 16);
            $encrypted = substr($data, 16);
            
            // Dekripsi
            $decrypted = openssl_decrypt($encrypted, $this->method, $this->key, OPENSSL_RAW_DATA, $iv);
            
            if ($decrypted === false) {
                // Get OpenSSL error
                $error = openssl_error_string();
                throw new Exception('OpenSSL decryption failed: ' . ($error ?: 'Unknown error'));
            }
            
            return $decrypted;
            
        } catch (Exception $e) {
            throw new Exception('Decryption failed: ' . $e->getMessage());
        }
    }
}
?>