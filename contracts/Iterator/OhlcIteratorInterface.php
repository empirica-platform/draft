<?php

namespace EmpiricaPlatform\Contracts;

// DataIterator
interface OhlcIteratorInterface extends \Iterator
{
    public function current(): OhlcInterface;
}
