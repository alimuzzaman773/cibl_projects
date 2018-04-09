<?php


class BOcrypter {

    private $Key;
    private $Algo;
    private $Mode;
    private $Iv;

    public function __construct() {
        $this->Algo = MCRYPT_RIJNDAEL_256; // AES
        $this->Mode = MCRYPT_MODE_ECB;
        $this->Key = substr(BO_PRIVATE_KEY, 0, mcrypt_get_key_size($this->Algo, $this->Mode));
    }

    public function Encrypt($data) {
        $iv_size = mcrypt_get_iv_size($this->Algo, $this->Mode);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $this->Iv = $iv;

        $blocksize = mcrypt_get_block_size('rijndael-256', 'ecb');                        // get block size
        $pkcs = $blocksize - (strlen($data) % $blocksize);                                // get pkcs5 pad length
        $data.= str_repeat(chr($pkcs), $pkcs);                                            // append pkcs5 padding to the data
        $crypt = mcrypt_encrypt($this->Algo, $this->Key, $data, $this->Mode, $this->Iv);
        return rtrim(base64_encode($crypt));
    }

    public function Decrypt($data) {
        $crypt = base64_decode($data);
        $decrypt = mcrypt_decrypt($this->Algo, $this->Key, $crypt, $this->Mode, $this->Iv);
        $block = mcrypt_get_block_size('rijndael-256', 'ecb');
        $pad = ord($decrypt[($len = strlen($decrypt)) - 1]);
        return substr($decrypt, 0, strlen($decrypt) - $pad);
    }
}

?>

