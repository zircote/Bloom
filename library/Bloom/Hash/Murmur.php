<?php
/**
 * @package
 * @category
 * @subcategory
 * Date: 6/30/12T10:32 PM
 */
namespace Bloom\Hash;
/**
 * @package
 * @category
 * @subcategory
 */
class Murmur implements HashInterface
{
    /**
     *
     */
    public function __construct()
    {
        if(!extension_loaded('murmurhash')){
            throw new \RuntimeException('murmurhash extension is not loaded');
        }
    }

    /**
     * @param string $hash_key
     * @param int    $bucket
     * @return int|number
     */
    public function hash($hash_key, $bucket = 0)
    {
        return abs(murmurhash($hash_key, $bucket));
    }
}
