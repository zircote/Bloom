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
 * @category    Tests
 * @subcategory Hash
 */

/**
 * @package     Bloom
 * @category    Tests
 * @subcategory Hash
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
