<?php

namespace EmpiricaPlatform\Terminal;

use EmpiricaPlatform\Plot\HistoryData\Ohlc;
use EmpiricaPlatform\Plot\HistoryData\OhlcList;
use EmpiricaPlatform\Terminal\Event\DataEvent;
use Nette\Utils\DateTime;

class Buffer
{
    public function __construct(
        public OhlcList $csvOhlc
    )
    {
    }

    /**
     * @param DataEvent $ohlc
     * @return void
     */
    public function addOhlc(DataEvent $ohlc): void
    {
        Ohlc::create(
            DateTime::from($ohlc->ohlc->getDateTime()),
            $ohlc->ohlc->getOpen()->getValue(),
            $ohlc->ohlc->getHigh()->getValue(),
            $ohlc->ohlc->getLow()->getValue(),
            $ohlc->ohlc->getClose()->getValue(),
            $ohlc->ohlc->getVolume()->getValue(),
            $this->csvOhlc,
            []
        );
    }
}