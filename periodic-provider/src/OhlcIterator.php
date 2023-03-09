<?php

namespace EmpiricaPlatform\PeriodicProvider;

use EmpiricaPlatform\Contracts\SymbolPairInterface;
use EmpiricaPlatform\Contracts\OhlcInterface;
use EmpiricaPlatform\Contracts\OhlcIteratorInterface;
use EmpiricaPlatform\PeriodicProvider\ValueObject\Ohlc;
use EmpiricaPlatform\PeriodicProvider\ValueObject\Price;
use EmpiricaPlatform\PeriodicProvider\ValueObject\Volume;
use DateTimeImmutable;
use ReturnTypeWillChange;

// https://en.wikipedia.org/wiki/Frequency
class OhlcIterator implements OhlcIteratorInterface
{
    private $index = 0;

    public function __construct(
        protected SymbolPairInterface $symbolPair,
    )
    {
    }

    public function rewind(): void
    {
        $this->index = 0;
    }

    #[ReturnTypeWillChange] public function current(): OhlcInterface
    {
        $vertical1 = 40;
        $xm = .2;
        $xCurr = $this->index * $xm;
        $xPrev = $xCurr - $xm;
        $openValue = sin(M_PI * $xPrev) + $vertical1;
        $closeValue = sin(M_PI * $xCurr) + $vertical1;
        $shadow = 1;

        $vertical2 = 50;
        $volume = sin(2 * M_PI * ($xPrev - 1)) * 50 + $vertical2;

        $dateTime = DateTimeImmutable::createFromMutable((new \DateTime())->setTime(0, $this->index));
        $open = new Price(round($openValue, 2));
        $high = new Price(round($openValue + $shadow, 2));
        $low = new Price(round($closeValue - $shadow, 2));
        $close = new Price(round($closeValue, 2));
        $volume = new Volume(round($volume, 2));

        return new Ohlc($this->symbolPair, $dateTime, $open, $high, $low, $close, $volume);
    }

    public function key(): int
    {
        return $this->index;
    }

    public function next(): void
    {
        ++$this->index;
    }

    public function valid(): bool
    {
        return $this->index <= 500;
    }

    // not need count(), runtime ready
    public function count(): int
    {
        return 500;
    }
}
