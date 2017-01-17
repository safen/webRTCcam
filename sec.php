<?php
class encryption
{
    const CIPHER = MCRYPT_RIJNDAEL_128;
    const MODE   = MCRYPT_MODE_CBC;
    private $key="a1kwy8ldGewlwqlm897rq83)ff!#tlkP";
//    public function __construct($key)
//    {
//        $this->key = $key;
//    }

    public function encrypt($text)
    {
        $ivSize = mcrypt_get_iv_size(self::CIPHER, self::MODE);
        $iv = mcrypt_create_iv($ivSize, MCRYPT_DEV_RANDOM);
        $encryptedData = mcrypt_encrypt(self::CIPHER, $this->key, $text, self::MODE, $iv);
        return base64_encode($iv.$encryptedData);
    }
    public function decrypt($encryptedData)
    {
        $encryptedData = base64_decode($encryptedData);
        $ivSize = mcrypt_get_iv_size(self::CIPHER, self::MODE);
        if (strlen($encryptedData) < $ivSize) {
            throw new Exception('Missing initialization vector.');
        }
        $iv = substr($encryptedData, 0, $ivSize);
        $encryptedData = substr($encryptedData, $ivSize);
        $text = mcrypt_decrypt(self::CIPHER, $this->key, $encryptedData, self::MODE, $iv);
        return rtrim($text, "\0");
    }
}

//$text = 'safen12';
//$crypt = new encryption();
//$encryptedString = $crypt->encrypt($text);
//echo 'encrypted:<br>'.$encryptedString."<br>";
//$decryptedString = $crypt->decrypt($encryptedString);
//
//
//echo 'decrypted:'.$decryptedString;
