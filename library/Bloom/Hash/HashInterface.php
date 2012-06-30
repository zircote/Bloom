<?php
/**
 * @package
 * @category
 * @subcategory
 * Date: 6/30/12T7:47 PM
 */
namespace Bloom\Hash;
/**
 * @package
 * @category
 * @subcategory
 */
interface HashInterface
{
    /**
     * @abstract
     * @param string $hash_key
     * @param int $  bucket
     * @return int
     */
    public function hash($hash_key, $bucket = 0);
}
