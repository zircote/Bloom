<?php
namespace BloomTests\Hash;

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
class MurmurTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Bloom\Hash\HashMix
     */
    protected $object;

    protected $_cycles = 10000;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
    }

    /**
     * 2.6631093025208E-5/operation
     */
    public function testHashMix()
    {
        $this->markTestSkipped();
        $this->object = new \Bloom\Hash\Murmur;
        echo PHP_EOL;
        $str = 'Robert Allen';
        $start = microtime(true);
        for($i = 0; $i < $this->_cycles; $i++){
            $value = $this->object->hash($str, $i);
            if($value < 1){
                echo $i, '::', $value, PHP_EOL;
            }
        }
        $end = microtime(true);
        echo ($end - $start) / $this->_cycles, PHP_EOL;
    }
}
