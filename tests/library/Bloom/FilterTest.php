<?php
namespace BloomTests;

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
class HashMixTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Bloom\Filter
     */
    protected $object;

    protected $_cycles = 10000;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new \Bloom\Filter(
            array('hashclass' => '\Bloom\Hash\Murmur')
        );
    }

    public function tearDown()
    {
//        $this->object
//            ->getRediska()
//            ->flushDb(true);
        $this->object = null;
    }

    public function testBenchMark()
    {
        $this->markTestSkipped('benchmark skipped');
        $elements = file(dirname(dirname(__DIR__)) . '/dic-0294.txt');
        echo 'Wordlist size:', count($elements), PHP_EOL;
        $start = microtime(true);
        $this->object->add($elements);
        echo 'save time:', microtime(true) - $start, PHP_EOL;

//        $start = microtime(true);
//        foreach ($elements as $el) {
//            $this->assertTrue($this->object->contains($el));
//        }
//        echo PHP_EOL, 'endtime:', microtime(true) - $start, PHP_EOL;
//        echo 'Total keys added:', $this->object->getNumberOfElements(),  PHP_EOL;
        $this->assertTrue($this->object->contains($elements));

    }
    /**
     * 2.6631093025208E-5/operation
     * @expectedException \RuntimeException
     */
    public function testAdd()
    {
//        $this->markTestIncomplete();
        $this->assertFalse($this->object->contains('aaa'));
        $this->object->add('aaa');
        $this->assertTrue($this->object->contains('aaa'));
    }

    public function testContains()
    {
        $this->markTestIncomplete();
        $this->object->add('aaa');
        $this->assertTrue($this->object->contains('aaa'));
        $this->assertFalse($this->object->contains('bbb'));
    }

    public function testAddMulti()
    {
        $this->markTestIncomplete();
        $data = array(
            'aaa', 'bbb', 'ccc', 'ddd'
        );
        $this->object->add($data);
        $this->assertTrue($this->object->contains($data));
    }

    public function testKeyCount()
    {
        $this->markTestIncomplete();
        $data = array(
            'aaa', 'bbb', 'ccc', 'ddd'
        );
        $this->object->add($data);
        $this->assertEquals(4, $this->object->getCount());
        $this->object->add('eee');
        $this->assertEquals(5, $this->object->getCount());
        $this->object->add($data);
        $this->assertEquals(5, $this->object->getCount());
        $this->object->add('eee');
        $this->assertEquals(5, $this->object->getCount());

    }

    public function testGetProbability()
    {
        $this->markTestIncomplete();
        $data = array(
            'aaa', 'bbb', 'ccc', 'ddd'
        );
        $this->object->add($data);
        $this->assertTrue($this->object->getFalsePositiveProbability() > 0);
        $this->assertTrue(
            $this->object->getFalsePositiveProbability() < 0.0000001
        );
    }
}
