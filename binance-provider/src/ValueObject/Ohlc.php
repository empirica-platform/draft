<?php

namespace EmpiricaPlatform\BinanceProvider\ValueObject;

use EmpiricaPlatform\Contracts\OhlcInterface;
use EmpiricaPlatform\Contracts\PriceInterface;
use EmpiricaPlatform\Contracts\SymbolPairInterface;
use EmpiricaPlatform\Contracts\VolumeInterface;
use DateTimeInterface;

class Ohlc implements OhlcInterface
{
    public function __construct(
        protected SymbolPairInterface $symbolPair,
        protected DateTimeInterface   $dateTime,
        protected PriceInterface      $open,
        protected PriceInterface      $high,
        protected PriceInterface      $low,
        protected PriceInterface      $close,
        protected VolumeInterface     $volume
    )
    {
    }

    public function getSymbolPair(): SymbolPairInterface
    {
        return $this->symbolPair;
    }

    public function getDateTime(): DateTimeInterface
    {
        return $this->dateTime;
    }

    public function getOpen(): PriceInterface
    {
        return $this->open;
    }

    public function getHigh(): PriceInterface
    {
        return $this->high;
    }

    public function getLow(): PriceInterface
    {
        return $this->low;
    }

    public function getClose(): PriceInterface
    {
        return $this->close;
    }

    public function getVolume(): VolumeInterface
    {
        return $this->volume;
    }
}
