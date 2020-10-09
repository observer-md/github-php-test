<?php
namespace Core\Support\Cryptography;

/**
 * Cipher class
 * 
 * @package Core\Support\Secure
 * @author  GEE
 */
class Generator
{
    /**
     * Generate serial ID. Ex: ZM51C-S2IDO-I32EA-KAAKT-Y0NNF
     * 
     * @param int $blocks   Number of blocks
     * @param int $size     Character size of each block
     * @return string
     */
    public static function serialId(int $blocks = 5, int $size = 5) : string
    {
        // List of IDs of alfa/numeric characters
        $chars = array_merge(range(0, 9), range('A', 'Z'));
        $max   = count($chars) - 1;

        $codes = [];
        for ($b = 0; $b < $blocks; $b++) {
            $code = '';
            for ($s = 0; $s < $size; $s++) {
                $i = random_int(0, $max);
                $code .= $chars[$i];
            }
            $codes[] = $code;
        }
        return join('-', $codes);
    }


    /**
     * Generate UUID v4
     * 
     * @link https://uuid.ramsey.dev/en/latest/rfc4122/version4.html
     * @link https://packagist.org/packages/ramsey/uuid
     * 
     * @return string
     */
    public static function uuid() : string
    {
        return \Ramsey\Uuid\Uuid::uuid4()->toString();
    }
}
