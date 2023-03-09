<?php

namespace EmpiricaPlatform\BinanceProvider\ValueObject;

use EmpiricaPlatform\Contracts\VolumeInterface;

class Volume implements VolumeInterface
{
    public function __construct(protected string $value)
    {
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
