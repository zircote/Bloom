<?php
/**
 * @package
 * @category
 * @subcategory
 * Date: 6/30/12T7:46 PM
 */
namespace Bloom;
use Rediska;

/**
 * @package
 * @category
 * @subcategory
 *
 * echo (1000000/PHP_INT_MAX) * log(2);
 * >>> 0.00032277180854358
 */
class Filter
{
    /**
     * @var string
     */
    const KEY_BIT_COUNT = 'bf:ka';
    /**
     * @var string
     */
    const KEY_BIT_VECTOR = 'bf:bv';
    /**
     * @var \Bloom\Hash\HashInterface
     */
    protected $_hash;
    /**
     * @var array
     */
    protected $_options = array(
        'buckets'    => 4,
        'keyprefix' => 'bf',
        'hashclass' => '\Bloom\Hash\HashMix'
    );
    /**
     * @var Rediska
     */
    protected $rediska;

    /**
     * @param array $options
     */
    public function __construct($options = array())
    {
        $this->setOptions($options);
    }

    public function setOptions($options)
    {
        $reflect = new \ReflectionClass($this);
        foreach ($options as $option => $value) {
            $method = 'set' . ucfirst($option);
            if($reflect->hasMethod($method)){
                $reflect->getMethod($method)->invokeArgs($this, $value);
            }
        }
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * @param integer $buckets
     * @return Filter
     */
    public function setBuckets($buckets)
    {
        $this->_options['buckets'] = $buckets;
        return $this;
    }

    /**
     * @param string $prefix
     * @return Filter
     */
    public function setKeyprefix($prefix)
    {
        $this->_options['keyprefix'] = $prefix;
        return $this;
    }

    /**
     * @param string $class
     * @return Filter
     */
    public function setHashclass($class)
    {
        $this->_options['hashclass'] = $class;
        return $this;
    }
    /**
     * @param $element
     */
    public function add($element)
    {
        try{
            $elements     = (array)$element;
//            $elements = array();
//            foreach ($element as $el) {
//                if(!$this->contains($el)){
//                    array_unshift($elements, $el);
//                }
//            }
            $transaction = $this
                ->getRediska()
                ->pipeline();
            if(!$elements){
                return false;
            }
            $i = 0;
            foreach ($elements as $el) {
//                $transaction->increment($this->_options['keyprefix'] . self::KEY_BIT_COUNT);
                foreach ($this->getHash($el) as $offset) {
                    $transaction->setBit(
                        $this->_options['keyprefix'] . self::KEY_BIT_VECTOR,
                        $offset, 1
                    );
                    if(++$i % 1000){
                        $transaction->execute();
                    }
                }
            }
            $result = $transaction->execute();
            return (bool) array_shift($result);
        } catch(\Rediska_Transaction_Exception $e) {
            echo $e->getMessage(), PHP_EOL;
            return false;
        }
    }

    /**
     * @param $element
     * @return bool
     */
    public function contains($element)
    {
        $element     = (array)$element;
        $transaction = $this
            ->getRediska()
            ->pipeline();
        foreach ($element as $el) {
            foreach ($this->getHash($el) as $offset) {
                $transaction->getBit(
                    $this->_options['keyprefix'] . self::KEY_BIT_VECTOR,
                    $offset
                );
            }
        }
        $result = $transaction->execute();
        return (bool)!in_array(0, $result);
    }

    /**
     * @return int
     */
    public function getNumberOfElements()
    {
        return (int)$this
            ->getRediska()
            ->get($this->_options['keyprefix'] . self::KEY_BIT_COUNT);
    }

    /**
     * @var string $element
     * @return array
     */
    public function getHash($element)
    {
        if(!$this->_hash){
            if($this->_options['hashclass'] instanceof \Bloom\Hash\HashInterface){
                $this->_hash = $this->_options['hashclass'];
            } else {
                $reflect = new \ReflectionClass($this->_options['hashclass']);
                if(!$reflect->implementsInterface('\Bloom\Hash\HashInterface')){
                    throw new \RuntimeException(
                        'hashclass must implement interface [\Bloom\Hash\HashInterface]'
                    );
                }
                $this->_hash = $reflect->newInstance();
            }
        }
        $hash = array();
        for ($i = 0; $i < $this->_options['buckets']; $i++) {
            array_unshift($hash, $this->_hash->hash($element, $i));
        }
        return (array)$hash;
    }

    /**
     * @param HashInterface $hash
     * @return Filter
     */
    public function setHash(\Bloom\Hash\HashInterface $hash)
    {
        $this->_hash = $hash;
        return $this;
    }

    /**
     * @param bool $numberOfElements
     * @return number
     */
    public function getFalsePositiveProbability()
    {
        // (1 - e^(-k * n / m)) ^ k
        return pow(
            (1 - exp(-$this->_options['buckets'] * $this->getCount() / PHP_INT_MAX)),
            $this->_options['buckets']
        );
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return (int) $this->getRediska()
            ->get($this->_options['keyprefix'] . self::KEY_BIT_COUNT);
    }

    /**
     * @return \Rediska
     */
    public function getRediska()
    {
        if(!$this->rediska instanceof \Rediska){
            $this->rediska = new \Rediska();
        }
        return $this->rediska;
    }

    /**
     * @param \Rediska $rediska
     */
    public function setRediska(\Rediska $rediska)
    {
        $this->rediska = $rediska;
    }
}
