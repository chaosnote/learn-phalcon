<?php

use Phalcon\Http\Response;
use Phalcon\Security\Random;

class RSACrypt
{
    private $privateKey;
    private $publicKey;

    public function __construct($privateKeyPath = null, $publicKeyPath = null)
    {
        if ($privateKeyPath) {
            $this->privateKey = file_get_contents($privateKeyPath);
        }
        if ($publicKeyPath) {
            $this->publicKey = file_get_contents($publicKeyPath);
        }
    }

    /**
     * 產生 (私、公) 鑰
     * @param string $privateKeyPath
     * @param string $publicKeyPath
     */
    public function generatePairKeys($privateKeyPath, $publicKeyPath)
    {
        $config = array(
            "digest_alg" => "sha512",
            "private_key_bits" => 1024,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        );

        $res = openssl_pkey_new($config);
        openssl_pkey_export($res, $privateKey);
        $publicKey = openssl_pkey_get_details($res)['key'];

        file_put_contents($privateKeyPath, $privateKey);
        file_put_contents($publicKeyPath, $publicKey);

        $this->privateKey = $privateKey;
        $this->publicKey = $publicKey;
    }

    /**
     * 加密
     * @param string $data
     */
    public function encrypt($data)
    {
        if (!$this->publicKey) {
            throw new Exception("Public key not set.");
        }
        openssl_public_encrypt($data, $encrypted, $this->publicKey);
        return base64_encode($encrypted);
    }

    /**
     * 解密
     * @param string $encryptedBase64
     */
    public function decrypt($encryptedBase64)
    {
        if (!$this->privateKey) {
            throw new Exception("Private key not set.");
        }
        $encrypted = base64_decode($encryptedBase64);
        openssl_private_decrypt($encrypted, $decrypted, $this->privateKey);
        return $decrypted;
    }

    /**
     * 簽署（Sign）
     * @param string $data
     */
    public function sign($data)
    {
        if (!$this->privateKey) {
            throw new Exception("Private key not set.");
        }
        openssl_sign($data, $signature, $this->privateKey, OPENSSL_ALGO_SHA256);
        return base64_encode($signature);
    }

    /**
     * 驗證（Verify）
     * @param string $data
     * @param string $signatureBase64
     */
    public function verify($data, $signatureBase64)
    {
        if (!$this->publicKey) {
            throw new Exception("Public key not set.");
        }
        $signature = base64_decode($signatureBase64);
        return openssl_verify($data, $signature, $this->publicKey, OPENSSL_ALGO_SHA256);
    }
}

class MD5Crypt
{
    /** 
     * @param string[] $strings 
     * */
    public static function generate(...$strings)
    {
        return hash('md5', implode("|", $strings));
    }

    public static function verify($data, $hash)
    {
        return hash('md5', $data) === $hash;
    }
}

// require_once 'RSACrypt.php';

class CryptController extends BaseController
{
    private $privateKeyPath = "../dist/crypt/private.pem";
    private $publicKeyPath = "../dist/crypt/public.pem";
    /** @var RSACrypt $rsa */
    private $rsa;

    private function init()
    {
        if (isset($this->rsa)) {
            return;
        }
        $this->rsa = new RSACrypt(); // $this->privateKeyPath, $this->publicKeyPath
        $this->rsa->generatePairKeys($this->privateKeyPath, $this->publicKeyPath);
    }

    /**
     * @Route("/crypt")
     */
    public function indexAction()
    {
        $this->init();

        $data = '機密資料';
        $encrypted = $this->rsa->encrypt($data);
        $decrypted = $this->rsa->decrypt($encrypted);

        echo "加密後的資料：" . $encrypted . "<br/>";
        echo "解密後的資料：" . $decrypted . "<br/>";
    }

    /**
     * @Route("/crypt/md5")
     */
    public function md5Action()
    {
        $md5 = new MD5Crypt();
        echo $md5->generate("123", "456") . "<br/>";
    }

    //
    // Cross-Site Request Forgery (CSRF) protection
    //

    /**
     * @Route("/crypt/isAllow")
     */
    public function isAllowAction()
    {
        // 檢查請求方法是否為 POST
        if ($this->request->isPost()) {
            // 處理 POST 請求
            // ... 您的程式碼 ...

            // 回應成功
            $response = new Response();
            $response->setStatusCode(200, 'OK');
            $response->setContent(json_encode(['message' => 'POST 請求成功']));
            $response->setContentType('application/json', 'UTF-8');
            return $response;
        } else {
            // 回應請求錯誤
            $response = new Response();
            $response->setStatusCode(405, 'Method Not Allowed');
            // $response->setContent(json_encode(['error' => '只允許 POST 請求']));
            $response->setContent('只允許 POST 請求');
            $response->setContentType('application/json', 'UTF-8');
            return $response;
        }
    }

    /**
     * @Route("/crypt/random")
     * @see https://docs.phalcon.io/3.4/api/Phalcon_Security/#class-phalconsecurityrandom
     */
    public function randomAction()
    {
        $random = new Random();

        // Random binary string
        $bytes = $random->bytes();

        $output = array(
            "Random hex string",
            $random->hex(10),
            $random->hex(10),
            $random->hex(11),
            $random->hex(12),
            $random->hex(13),
            "Random base62 string",
            $random->base62(),
            "Random base64 string",
            $random->base64(12),
            $random->base64(12),
            $random->base64(),
            $random->base64(16),
            "Random URL-safe base64 string",
            $random->base64Safe(),
            $random->base64Safe(),
            $random->base64Safe(8),
            $random->base64Safe(null, true),
            "Random UUID",
            $random->uuid(),
            $random->uuid(),
            $random->uuid(),
            $random->uuid(),
            "Random number between 0 and \$len",
            $random->number(256),
            $random->number(256),
            $random->number(100),
            $random->number(300),
            "Random base58 string",
            $random->base58(),
            $random->base58(),
            $random->base58(24),
            $random->base58(7),
        );

        echo implode("<br>", $output);
    }
}
