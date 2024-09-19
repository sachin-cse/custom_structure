<?php
class Security 
{
    # Private key
    public static $salt = SITE_PRIVATE_KEY;
  
    # Encrypt a value using AES-256.
    public static function encrypt($plain, $key, $hmacSalt = null) {
        self::_checkKey($key, 'encrypt()');
 
        if ($hmacSalt === null) {
            $hmacSalt = self::$salt;
        }
 
        $key = substr(hash('sha256', $key . $hmacSalt), 0, 32); # Generate the encryption and hmac key
 
        $algorithm = MCRYPT_RIJNDAEL_128; # encryption algorithm
        $mode = MCRYPT_MODE_CBC; # encryption mode
 
        $ivSize = mcrypt_get_iv_size($algorithm, $mode); # Returns the size of the IV belonging to a specific cipher/mode combination
        $iv = mcrypt_create_iv($ivSize, MCRYPT_DEV_URANDOM); # Creates an initialization vector (IV) from a random source
        $ciphertext = $iv . mcrypt_encrypt($algorithm, $key, $plain, $mode, $iv); # Encrypts plaintext with given parameters
        $hmac = hash_hmac('sha256', $ciphertext, $key); # Generate a keyed hash value using the HMAC method
        return $hmac . $ciphertext;
    }
 
    # Check key
    protected static function _checkKey($key, $method) {
        if (strlen($key) < 32) {
            echo "Invalid public key $key, key must be at least 256 bits (32 bytes) long."; die();
        }
    }
    
    function apiToken($session_uid)
    {
        $key=md5(SITE_PRIVATE_KEY.$session_uid);
        return hash('sha256', $key);
    }
 
    # Decrypt a value using AES-256.
    public static function decrypt($cipher, $key, $hmacSalt = null) {
        self::_checkKey($key, 'decrypt()');
        if (empty($cipher)) {
            echo 'The data to decrypt cannot be empty.'; die();
        }
        if ($hmacSalt === null) {
            $hmacSalt = self::$salt;
        }
 
        $key = substr(hash('sha256', $key . $hmacSalt), 0, 32); # Generate the encryption and hmac key.
 
        # Split out hmac for comparison
        $macSize = 64;
        $hmac = substr($cipher, 0, $macSize);
        $cipher = substr($cipher, $macSize);
 
        $compareHmac = hash_hmac('sha256', $cipher, $key);
        if ($hmac !== $compareHmac) {
            return false;
        }
 
        $algorithm = MCRYPT_RIJNDAEL_128; # encryption algorithm
        $mode = MCRYPT_MODE_CBC; # encryption mode
        $ivSize = mcrypt_get_iv_size($algorithm, $mode); # Returns the size of the IV belonging to a specific cipher/mode combination
 
        $iv = substr($cipher, 0, $ivSize);
        $cipher = substr($cipher, $ivSize);
        $plain = mcrypt_decrypt($algorithm, $key, $cipher, $mode, $iv);
        return rtrim($plain, "\0");
    }
     
    #Get Random String - Usefull for public key
    public function genRandString($length = 0) {
        $charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $str = '';
        $count = strlen($charset);
        while ($length-- > 0) {
            $str .= $charset[mt_rand(0, $count-1)];
        }
		return $str;
    }
    
    public function cleanInputs($data){
        $clean_input = array();
        if(is_array($data)){
            foreach($data as $k => $v){
                $clean_input[$k] = $this->cleanInputs($v);
            }
        }else{
            if(get_magic_quotes_gpc()){
                $data = trim(stripslashes($data));
            }
            $data = strip_tags($data);
            $clean_input = trim($data);
            $clean_input = escapeshellcmd($data);
            $clean_input = escapeshellarg($data);
            $clean_input = htmlspecialchars($data);
        }
        return $clean_input;
    } 
}?>