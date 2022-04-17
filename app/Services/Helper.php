<?php


namespace App\Services;


class Helper
{
    public static function cutString(
        string $str,
        int $length
    ) : string {
        $postfix = strlen($str) > $length
            ? '..'
            : '';

        return substr($str,
                0,
                $length) . $postfix;
    }
}