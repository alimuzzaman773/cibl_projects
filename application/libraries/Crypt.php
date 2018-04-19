<?php

class Crypt {

    public $Algo;
    public $Mode;

    public function __construct() {
        $this->Algo = MCRYPT_BLOWFISH;
        $this->Mode = MCRYPT_MODE_CBC;
    }

    public function ivGenerator() {
        $ivSize = mcrypt_get_iv_size($this->Algo, $this->Mode);
        $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
        return base64_encode($iv);
    }

    public function encrypt($key, $data, $iv) {
        $iv = base64_decode($iv);
        $blockSize = mcrypt_get_block_size($this->Algo, $this->Mode);
        $pkcs = $blockSize - (strlen($data) % $blockSize);
        $data .= str_repeat(chr($pkcs), $pkcs);
        $encrypt = mcrypt_encrypt($this->Algo, $key, $data, $this->Mode, $iv);
        return rtrim(base64_encode($encrypt));
    }
    
    function encrypt_openssl($key, $data,$iv) {
      //$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
      $encrypted = openssl_encrypt($data, 'BF-CBC', $key, OPENSSL_ZERO_PADDING, $iv);
      return base64_encode($encrypted . '::' . $iv);
    }

    public function decrypt($key, $data, $iv) {
        $encrypt = base64_decode($data);
        $iv = base64_decode($iv);
        $decrypt = mcrypt_decrypt($this->Algo, $key, $encrypt, $this->Mode, $iv);
        $pad = ord($decrypt[($len = strlen($decrypt)) - 1]);
        return substr($decrypt, 0, strlen($decrypt) - $pad);
    }
    
    function decrypt_openssl($key, $garble, $iv) {
        list($encrypted_data, $ivs) = explode('::', base64_decode($garble), 2);
        return openssl_decrypt($encrypted_data, 'BF-CBC', $key, OPENSSL_ZERO_PADDING, $iv);
    }

}

?>