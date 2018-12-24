**前言**
互联网的发展史上，安全性一直是开发者们相当重视的一个主题，为了实现数据传输安全，我们需要保证：数据来源（非伪造请求）、数据完整性（没有被人修改过）、数据私密性（密文，无法直接读取）等。
虽然现在已经有SSL/TLS协议实现的HTTPS协议，但是因在客户端上依赖浏览器的正确实现，而且效率又很低，所以一般的敏感数据（如交易支付信息等）还是需要我们使用加密方法来手动加密。
加密算法一般分为两种：对称加密算法和非对称加密算法。
**1.对称加密**
对称加密算法是消息发送者和接收者使用同一个密匙，发送者使用密匙加密了文件，接收者使用同样的密匙解密，获取信息。常见的对称加密算法有：DES/AES.

对称加密算法的特点有：速度快，加密前后文件大小变化不大，但是密匙的保管是个大问题，因为消息发送方和接收方任意一方的密匙丢失，都会导致信息传输变得不安全。

PHP代码:
string openssl_encrypt ( string $data , string $method , string $key [, int $options = 0 [, string $iv = "" [, string &$tag = NULL [, string $aad = "" [, int $tag_length = 16 ]]]]] )

参数
data
待加密的明文信息数据。

method
密码学方式。openssl_get_cipher_methods() 可获取有效密码方式列表。

key
key。

options
options 是以下标记的按位或： OPENSSL_RAW_DATA 、 OPENSSL_ZERO_PADDING。

iv
非 NULL 的初始化向量。

tag
使用 AEAD 密码模式（GCM 或 CCM）时传引用的验证标签。

aad
附加的验证数据。

tag_length
验证 tag 的长度。GCM 模式时，它的范围是 4 到 16。

返回值
成功时返回加密后的字符串， 或者在失败时返回 FALSE。
```
//加密
function encrypt_openssl() {
    $iv_key = 'testivkey'; //自定义
    $iv  = substr(md5($iv_key), 8, 16);
    $key = 'testkey';//自定义
    openssl_encrypt($name, 'AES-256-CBC', $key, 0, $iv);
 }
 
 //解密
function decrypt_openssl() {
    $iv_key = 'testivkey'; //自定义
    $iv  = substr(md5($iv_key), 8, 16);
    $key = 'testkey';//自定义
    openssl_encrypt($name, 'AES-256-CBC', $key, 0, $iv);
}
```
**2.非对称加密**
与对称加密相对的是非对称加密，非对称加密的核心思想是使用一对相对的密匙，分为公匙和私匙，私匙自己安全保存，而将公匙公开。
公钥与私钥是一对，如果用公钥对数据进行加密，只有用对应的私钥才能解密；如果用私钥对数据进行加密，那么只有用对应的公钥才能解密。
发送数据前只需要使用接收方的公匙加密就行了。常见的非对称加密算法有RSA/DSA:

非对称加密虽然没有密匙保存问题，但其计算量大，加密速度很慢,有时候我们还需要对大块数据进行分块加密。

PHP代码例子:
```
    class Rsa {
        public $privateKey = '';
        public $publicKey = '';
    
        public function __construct() {
            $resource = openssl_pkey_new(); //生成一个新的私钥
            openssl_pkey_export($resource, $this->privateKey); //将一个密钥的可输出表示转换为字符串
            $detail = openssl_pkey_get_details($resource); //返回包含密钥详情的数组,key持有密钥的资源。
            $this->publicKey = $detail['key'];
        }
    
        public function publicEncrypt($data, $publicKey) {
            openssl_public_encrypt($data, $encrypted, $publicKey);
            return $encrypted;
        }
    
        public function publicDecrypt($data, $publicKey) {
            openssl_public_decrypt($data, $decrypted, $publicKey);
            return $decrypted;
        }
    
        public function privateEncrypt($data, $privateKey) {
            openssl_private_encrypt($data, $encrypted, $privateKey);
            return $encrypted;
        }
    
        public function privateDecrypt($data, $privateKey) {
            openssl_private_decrypt($data, $decrypted, $privateKey);
            return $decrypted;
        }
    }
    $rsa = new Rsa;
    // 使用公钥加密
    $str = $rsa->publicEncrypt('hello', $rsa->publicKey);
    // 这里使用base64是为了不出现乱码，默认加密出来的值可能有乱码
    $encrypt_str = base64_encode($str);
    $str = base64_decode($encrypt_str);
    $privstr = $rsa->privateDecrypt($str, $rsa->privateKey);
    echo "私钥解密：\n", $privstr, "\n";
```

