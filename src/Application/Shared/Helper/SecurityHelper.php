<?php

declare(strict_types=1);

namespace App\Application\Shared\Helper;

class SecurityHelper implements SecurityHelperInterface
{
    private const METHOD = 'aes-256-cbc';

    public function __construct(
        private string $secret,
    ) {}

    public function encryptString(string $data): string
    {
        $initializationVectorLength = openssl_cipher_iv_length(self::METHOD);
        $initializationVector = openssl_random_pseudo_bytes($initializationVectorLength);

        $encryptedString = openssl_encrypt(
            $data,
            self::METHOD,
            $this->secret,
            OPENSSL_RAW_DATA,
            $initializationVector
        );

        return base64_encode($initializationVector . $encryptedString);
    }

    public function decryptString(string $data): string
    {
        $initializationVectorLength = openssl_cipher_iv_length(self::METHOD);
        $data = base64_decode($data);
        $initializationVector = substr($data, 0, $initializationVectorLength);
        $raw = substr($data, $initializationVectorLength);

        return openssl_decrypt(
            $raw,
            self::METHOD,
            $this->secret,
            OPENSSL_RAW_DATA,
            $initializationVector
        );
    }
}
