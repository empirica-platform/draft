<?php

namespace EmpiricaPlatform\Terminal\Event;

use EmpiricaPlatform\Contracts\Ohlc;
use Symfony\Contracts\EventDispatcher\Event;

class HistoryDataEvent extends Event
{
    public function __construct(
        public readonly Ohlc $ohlc
    )
    {
    }
}