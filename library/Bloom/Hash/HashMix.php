<?php
/**
 * @package
 * @category
 * @subcategory
 * Date: 6/30/12T6:53 PM
 */
namespace Bloom\Hash;
/**
 * @package
 * @category
 * @subcategory
 */
class HashMix implements HashInterface
{
    /**
     * @param string $hash_key
     * @param int    $bucket
     * @return int|number
     */
    public function hash($hash_key, $bucket = 0)
    {
        $a = strlen($hash_key);
        $b = 0xff1 * (int)$bucket;
        $c = $hash_key;
        $a -= ($b + $c);
        $a ^= ($c >> 13);
        $b -= ($c + $a);
        $b ^= ($a << 8);
        $c -= ($a + $b);
        $c ^= ($b >> 13);
        $a -= ($b + $c);
        $a ^= ($c >> 12);
        $b -= ($c + $a);
        $b ^= ($a << 16);
        $c -= ($a + $b);
        $c ^= ($b >> 5);
        $a -= ($b + $c);
        $a ^= ($c >> 3);
        $b -= ($c + $a);
        $b ^= ($a << 10);
        $c -= ($a + $b);
        $c ^= ($b >> 15);
        return abs($c);
    }

}
