<?php

namespace EmpiricaPlatform\Contracts;

class Ohlc
{
    public function __construct(
        public readonly \DateTimeInterface $time,
        public readonly string $baseSymbol,
        public readonly string $quoteSymbol,
        public readonly float $openPrice,
        public readonly float $highPrice,
        public readonly float $lowPrice,
        public readonly float $closePrice,
        public readonly float $volume
    )
    {
    }
}
