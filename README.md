Bloom
=====

This project has morphed from an attempt of 'pure' php bloom filters to a tool
that employs `Redis` and `Rediska` as a storage and interface for a distributed
`Bloom Filter`.

By utilizing the power of redis' `SETBIT` and `GETBIT` we are able to create a
simple, powerful and lightweight bloomfilter. The ability of redis to create a
BITSET that is practically endless in size _512MB_ the index limitations afforded
us is huge. PHP_INT_MAX serves well as a line in the sand in this early stage of
testing and research. This potentially results in a 3% false positive rate for
10b values given sufficient number of hashing buckets. (I am performing more on
these numbers before I get overly confident on these findings.)


```php
<?php
use Bloom\Filter,
    Bloom\Hash\HashMix,
    Bloom\Hash\Murmur,
    Rediska;

/* Create the Filter */
$filter = new Filter();
/* Inject Rediska */
$filter->setRediska(new Rediska());
/* Inject the Hash Class */
if(extension_loaded('murmurhash')){
    $filter->setHash(new Murmur());
} else {
    $filter->setHash(new HashMix());
}

/* Add items to the filter */
$filter->add('some random text');
$filter->add(array('or add','an array of elements'));

/* Check the filter */
var_dump($filter->contains('some random text'));
// bool(true)

var_dump($filter->contains('or add'));
// bool(true)

var_dump($filter->contains('This is not in the filter'));
// bool(false)


var_dump($filter->contains(array('or add','an array of elements')));
// bool(true)

var_dump($filter->contains(array('NO','or add','an array of elements')));
// bool(false)

```
