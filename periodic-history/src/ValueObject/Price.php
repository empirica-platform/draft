<?php

namespace EmpiricaPlatform\PeriodicHistory\ValueObject;

use EmpiricaPlatform\Contracts\PriceInterface;

class Price implements PriceInterface
{
    public function __construct(protected string $value)
    {
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
