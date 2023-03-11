<?php

namespace EmpiricaPlatform\Terminal\DataProvider;

use EmpiricaPlatform\Contracts\OhlcIteratorInterface;
use EmpiricaPlatform\Terminal\Event\ConsoleCommandEvent;
use EmpiricaPlatform\Terminal\Event\HistoryDataEvent;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AutoconfigureTag('kernel.event_listener', ['method' => 'onCommand', 'event' => ConsoleCommandEvent::class])]
class HistoryDataProvider
{
    public function __construct(
        protected EventDispatcherInterface $dispatcher,
        protected OhlcIteratorInterface $iterator
    )
    {
    }

    public function onCommand(ConsoleCommandEvent $event): void
    {
        foreach ($this->iterator as $ohlc) {
            $this->dispatcher->dispatch(new HistoryDataEvent($ohlc));
        }
    }
}
