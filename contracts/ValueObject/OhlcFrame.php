<?php

namespace EmpiricaPlatform\Contracts;

class OhlcFrame
{
    public function __construct(
        public readonly \DateTimeInterface $time,//todo del
        public readonly string $baseSymbol,//todo del
        public readonly string $quoteSymbol,//todo del

        public readonly float $openPrice,
        public readonly float $highPrice,
        public readonly float $lowPrice,
        public readonly float $closePrice,
        public readonly float $volume
    )
    {
    }
}
