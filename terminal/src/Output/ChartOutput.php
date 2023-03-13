<?php

namespace EmpiricaPlatform\Terminal\Output;

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
use EmpiricaPlatform\Terminal\Event\ConsoleTerminateEvent;
use EmpiricaPlatform\Terminal\Event\HistoryDataEvent;
use Nette\Utils\DateTime;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AutoconfigureTag('kernel.event_listener', ['method' => 'addOhlc', 'event' => HistoryDataEvent::class])]
#[AutoconfigureTag('kernel.event_listener', ['method' => 'renderFile', 'event' => ConsoleTerminateEvent::class])]
class ChartOutput
{
    private OhlcList $csvOhlc;
    private DrawCanvas $canvas;

    public function __construct(
        #[Autowire('%plot_file%')] private string $file,
        #[Autowire('%plot_font%')] private string $font
    )
    {
        $this->csvOhlc = OhlcList::create(0);
        $this->canvas =  DrawCanvas::createCanvas(1000, 600);
        $this->canvas->setFontPath($this->font);
    }

    /**
     * @param HistoryDataEvent $event
     * @return void
     */
    public function addOhlc(HistoryDataEvent $event): void
    {

        Ohlc::create(
            DateTime::from($event->ohlc->time),
            $event->ohlc->openPrice,
            $event->ohlc->highPrice,
            $event->ohlc->lowPrice,
            $event->ohlc->closePrice,
            $event->ohlc->volume,
            $this->csvOhlc
        );
    }

    /**
     * @param ConsoleTerminateEvent $event
     * @return void
     * @throws DrawCanvasException
     */
    public function renderFile(ConsoleTerminateEvent $event): void
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
