<?php
/**
 * Created by PhpStorm.
 * User: liulei
 * Date: 17/3/30
 * Time: 下午10:39
 */
class AES {
    private $_secret_key = '#@5#!89$';

    public function setKey($key) {
        $this->_secret_key = $key;
    }
    private static function pkcs5_pad ($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }
    public function encrypt($data) {

        $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
        $input = AES::pkcs5_pad($data, $size);
        // 打开mcrypt，或者mcrypt类型的资源对象，该对象使用ecb模式，使用3des作为加密算法。
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
        // 创建iv(初始化向量)
        $iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        // 根据密钥和iv初始化$td,完成内存分配等初始化工作
        mcrypt_generic_init($td, $this->_secret_key, $iv);
        // 进行加密
        $data = mcrypt_generic($td, $input);
        // 反初始化$td,释放资源
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data = base64_encode($data);
        return $data;
    }

    public function decrypt($data) {
        $decrypted= mcrypt_decrypt(
            MCRYPT_RIJNDAEL_128,
            $this->_secret_key,
            base64_decode($data),
            MCRYPT_MODE_ECB
        );
        $dec_s = strlen($decrypted);
        $padding = ord($decrypted[$dec_s-1]);
        $decrypted = substr($decrypted, 0, -$padding);
        return $decrypted;
    }
}