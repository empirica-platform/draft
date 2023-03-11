<?php

namespace EmpiricaPlatform\Terminal\Event;

use EmpiricaPlatform\Contracts\OhlcInterface;
use Symfony\Contracts\EventDispatcher\Event;

class HistoryDataEvent extends Event
{
    public function __construct(
        public OhlcInterface $ohlc
    )
    {
    }
}