<?php

namespace EmpiricaPlatform\PeriodicHistory;

use EmpiricaPlatform\Contracts\Ohlc;

class OhlcIterator implements \Iterator
{
    protected string $baseSymbol;
    protected string $quoteSymbol;
    protected \DateTimeImmutable $timeFrom;
    protected \DateTimeImmutable $timeTo;
    protected \DateInterval $timeFrame;

    private int $key = 0;

    /**
     * @param string $baseSymbol
     * @param string $quoteSymbol
     * @param string $timeFrom
     * @param string $timeTo
     * @param string $timeFrame
     * @throws \Exception
     */
    public function __construct(string $baseSymbol, string $quoteSymbol, string $timeFrom, string $timeTo, string $timeFrame)
    {
        $this->baseSymbol = $baseSymbol;
        $this->quoteSymbol = $quoteSymbol;
        $this->timeFrom = new \DateTimeImmutable($timeFrom);
        $this->timeTo = new \DateTimeImmutable($timeTo);
        $this->timeFrame = new \DateInterval($timeFrame);
    }

    public function rewind(): void
    {
        $this->key = 0;
    }

    public function current(): Ohlc
    {
        $vertical1 = 40;
        $xm = .2;
        $xCurr = $this->key * $xm;
        $xPrev = $xCurr - $xm;
        $openValue = sin(M_PI * $xPrev) + $vertical1;
        $closeValue = sin(M_PI * $xCurr) + $vertical1;
        $shadow = 1;

        $vertical2 = 50;
        $volume = sin(2 * M_PI * ($xPrev - 1)) * 50 + $vertical2;

        $ohlc = new Ohlc(
            \DateTimeImmutable::createFromMutable((new \DateTime())->setTime(0, $this->key)),
            $this->baseSymbol,
            $this->quoteSymbol,
            round($openValue, 2),
            round($openValue + $shadow, 2),
            round($closeValue - $shadow, 2),
            round($closeValue, 2),
            round($volume, 2)
        );

        return $ohlc;
    }

    public function key(): int
    {
        return $this->key;
    }

    public function next(): void
    {
        ++$this->key;
    }

    public function valid(): bool
    {
        return $this->key <= 500;
    }
}
