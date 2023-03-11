<?php

namespace EmpiricaPlatform\Terminal\DataProvider;

use EmpiricaPlatform\Contracts\OhlcIteratorInterface;
use EmpiricaPlatform\Terminal\Event\ConsoleCommandEvent;
use EmpiricaPlatform\Terminal\Event\HistoryDataEvent;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AutoconfigureTag('kernel.event_listener', ['method' => 'onCommand', 'event' => ConsoleCommandEvent::class])]
class HistoryDataProvider
{
    public function __construct(
        #[Autowire(service: 'event_dispatcher')] protected EventDispatcherInterface $dispatcher,
        #[Autowire(service: 'data_iterator')] protected OhlcIteratorInterface $iterator
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
