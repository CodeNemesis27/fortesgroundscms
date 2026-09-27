<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use RuntimeException;

/**
 * Handles file-level encryption using envelope encryption:
 *
 *  1. A fresh, random 256-bit Data Encryption Key (DEK) is generated for
 *     every single file/version — a leaked key only ever exposes one file.
 *  2. The file is encrypted with that DEK using AES-256-GCM, an
 *     authenticated cipher: any tampering or corruption of the ciphertext
 *     is detected at decrypt time via the auth tag, rather than silently
 *     producing garbage plaintext (which plain AES-CBC would do).
 *  3. The DEK itself is "wrapped" — encrypted — using the application's
 *     master key (APP_KEY) via Laravel's Crypt facade, and only the
 *     wrapped DEK is persisted.
 *
 * The benefit of this pattern over encrypting every file directly with
 * APP_KEY is key rotation: rotating APP_KEY only requires re-wrapping the
 * small DEKs, not re-encrypting every stored file from scratch.
 */

class DocumentEncryptionService
{
    private const CIPHER = 'aes-256-gcm';

    /**
     * @return array{ciphertext: string, encrypted_data_key: string, iv: string, tag: string, cipher: string}
     */
    public function encrypt(string $plaintext): array
    {
        $dek = random_bytes(32); // 256-bit key, used once
        $iv = random_bytes(12);  // 96-bit nonce, required for GCM

        $tag = '';
        $ciphertext = openssl_encrypt(
            $plaintext,
            self::CIPHER,
            $dek,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );

        if ($ciphertext === false) {
            throw new RuntimeException('Failed to encrypt document contents.');
        }

        return [
            'ciphertext' => $ciphertext,
            'encrypted_data_key' => Crypt::encryptString(base64_encode($dek)),
            'iv' => bin2hex($iv),
            'tag' => bin2hex($tag),
            'cipher' => self::CIPHER,
        ];
    }

    public function decrypt(string $ciphertext, string $encryptedDataKey, string $ivHex, string $tagHex): string
    {
        $dek = base64_decode(Crypt::decryptString($encryptedDataKey));
        $iv = hex2bin($ivHex);
        $tag = hex2bin($tagHex);

        $plaintext = openssl_decrypt(
            $ciphertext,
            self::CIPHER,
            $dek,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );

        if ($plaintext === false) {
            // GCM auth tag mismatch: the ciphertext was tampered with or corrupted.
            throw new RuntimeException('Document integrity check failed — the file may have been tampered with or corrupted.');
        }

        return $plaintext;
    }
}
