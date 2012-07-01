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
     * @var int
     */
    protected $k = 4;
    /**
     * @var array
     */
    protected $_options = array(
        'k'          => 4,
        'key_prefix' => 'bf'
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

    }

    /**
     * @param $element
     */
    public function add($element)
    {
        $element     = (array)$element;
        $transaction = $this
            ->getRediska()
            ->transaction();
        foreach ($element as $el) {
            foreach ($this->getHash($el) as $offset) {
                $transaction->setBit(
                    $this->_options['key_prefix'] . self::KEY_BIT_VECTOR,
                    $offset, 1
                );
            }
        }
        $result = $transaction->execute();
        return (bool) array_shift($result);
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
            ->transaction();
        foreach ($element as $el) {
            foreach ($this->getHash($el) as $offset) {
                $transaction->getBit(
                    $this->_options['key_prefix'] . self::KEY_BIT_VECTOR,
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
            ->get($this->_options['key_prefix'] . self::KEY_BIT_COUNT);
    }

    /**
     * @var string $element
     * @return array
     */
    public function getHash($element)
    {
        $hash = array();
        for ($i = 0; $i < $this->k; $i++) {
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
    public function getFalsePositiveProbability($numberOfElements = false)
    {
        // (1 - e^(-k * n / m)) ^ k
        return pow(
            (1 - exp(-$this->k * $this->getNumberOfElements() / PHP_INT_MAX)),
            $this->k
        );
    }

    /**
     * @return \Rediska
     */
    public function getRediska()
    {
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
