<?php
namespace App\Libraries;
use Exception;

class RandomString
{
    /**
     * this method used for generating random string value
     * @param int $length panjang string random yang diinginkan
     * @return string random string with request length
     */
    public static function random_string(int $length): string
    {
        try {
            $str = random_bytes($length);
            $str = base64_encode($str);
            $str = str_replace(["+", "/", "="], "", $str);
        } catch (Exception $e) {
            echo $e->getMessage();
            echo "its from RandomString";
        }

        return  substr($str, 0, $length);
    }
}