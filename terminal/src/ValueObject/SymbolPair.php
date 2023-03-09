<?php

namespace EmpiricaPlatform\Terminal\ValueObject;

use EmpiricaPlatform\Contracts\SymbolEnum;
use EmpiricaPlatform\Contracts\SymbolPairInterface;

class SymbolPair implements SymbolPairInterface
{
    public function __construct(
        protected SymbolEnum $base,
        protected SymbolEnum $quote
    )
    {}

    public function getBase(): SymbolEnum
    {
        return $this->base;
    }

    public function getQuote(): SymbolEnum
    {
        return $this->quote;
    }

    public function __toString(): string
    {
        return $this->quote . '/' . $this->quote;
    }
}
