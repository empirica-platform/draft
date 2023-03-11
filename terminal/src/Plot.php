<?php

namespace EmpiricaPlatform\Terminal;

use EmpiricaPlatform\Plot\DrawImage\DrawAvgVolume;
use EmpiricaPlatform\Plot\DrawImage\DrawBgOhlcList;
use EmpiricaPlatform\Plot\DrawImage\DrawBorder;
use EmpiricaPlatform\Plot\DrawImage\DrawCanvas;
use EmpiricaPlatform\Plot\DrawImage\DrawOhlcList;
use EmpiricaPlatform\Plot\DrawImage\DrawRSI;
use EmpiricaPlatform\Plot\DrawImage\DrawSingleValue;
use EmpiricaPlatform\Plot\DrawImage\DrawVolume;
use EmpiricaPlatform\Plot\DrawImage\Exception\DrawCanvasException;
use EmpiricaPlatform\Plot\HistoryData\Ohlc;
use EmpiricaPlatform\Plot\HistoryData\OhlcList;
use EmpiricaPlatform\Plot\MovingAverage\Sma;
use EmpiricaPlatform\Terminal\Event\DataEvent;
use EmpiricaPlatform\Terminal\Event\EndEvent;
use Nette\Utils\DateTime;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('kernel.event_listener', ['method' => 'addOhlc', 'event' => DataEvent::class])]
#[AutoconfigureTag('kernel.event_listener', ['method' => 'renderFile', 'event' => EndEvent::class])]
class Plot
{
    public function __construct(
        private OhlcList $csvOhlc,
        private DrawCanvas $canvas,
        private string $file
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
            $this->csvOhlc
        );
    }

    /**
     * @param EndEvent $event
     * @return void
     * @throws DrawCanvasException
     */
    public function renderFile(EndEvent $event): void
    {
        $drawPrice = DrawOhlcList::create(
            $this->csvOhlc,
            DrawBorder::create($this->canvas)
        );
        DrawBgOhlcList::createBg($drawPrice)
            ->setProductName('Apple')
            ->setUnits('$', '%02d')
        ;
        $drawVolume = DrawVolume::create(75, $drawPrice);
        DrawRSI::create(75, $drawPrice, 14);
        DrawRSI::create(75, $drawPrice, 14);
        DrawAvgVolume::create($drawVolume);


        $sma = Sma::create($this->csvOhlc,5);
        DrawSingleValue::create($sma,$drawPrice);

        ob_start();
        $this->canvas->drawImage();
        $contents = ob_get_clean();
        file_put_contents($this->file, $contents);
    }
}
