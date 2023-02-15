<?php

namespace App\Services\ChatBot;

use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Str;

class ChatbotEncryter
{
    /**
     * @var string
     */
    private string $key;

    private string $cipher;

    public function __construct()
    {
        $key = config('bot.app_key');

        if (!$key) {
            throw new \Exception("Chatbot encryption key is missing");
        }
        $this->key = $this->parseKey($key);
        $this->cipher = 'AES-256-CBC';
    }

    /**
     * getting encrypter instance
     */
    private function getEncrypter(): Encrypter
    {
        return new Encrypter($this->key, $this->cipher);
    }

    /**
     * parsing the if its base64
     */
    protected function parseKey(string $key): bool|string
    {
        if (Str::startsWith($key, $prefix = 'base64:')) {
            $key = base64_decode(Str::after($key, $prefix));
        }

        return $key;
    }



    /**
     * Encrypting string
     */
    public static function encryptString(?string $text): ?string
    {
        return $text ? (new static())->getEncrypter()->encryptString($text) : null;
    }



    /**
     * Decrypting string
     */
    public static function decryptString(?string $text): ?string
    {
        return $text ? (new static())->getEncrypter()->decryptString($text) : null;
    }


}
