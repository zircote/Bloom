Bloom
=====

a drunken investigation into feasibility of pure php bloom filters

```php
<?php
use Bloom\Filter,
    Bloom\Hash\HashMix,
    Rediska;

/* Create the Filter */
$filter = new Filter();
/* Inject Rediska */
$filter->setRediska(new Rediska());
/* Inject the Hash Class */
$filter->setHash(new HashMix());

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
