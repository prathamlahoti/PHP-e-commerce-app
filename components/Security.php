<?php

namespace App\components;

class Security
{
    public static function generateFileName($fileName)
    {
        return strtolower(md5($fileName)).'.jpg';
    }

    public static function generateToken()
    {
        $csrf_token = '';
        if (version_compare(PHP_VERSION, '7.0.0', '>=')) {
            $csrf_token = bin2hex(random_bytes(32));
        } else {
            $csrf_token = bin2hex(openssl_random_pseudo_bytes(32));
        }

        $_SESSION['csrf_'] = $csrf_token;

        return $csrf_token;
    }

    public static function hashComparison($var1, $var2)
    {
        return hash_equals($var1, $var2);
    }
}
