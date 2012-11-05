<?php
namespace Bloom\Hash;

/**
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * Copyright [2012] [Robert Allen]
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package     Bloom
 * @category    Hash
 */

/**
 * @package     Bloom
 * @category    Hash
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
