<?php

namespace EmpiricaPlatform\Terminal\EventEmitter;

use EmpiricaPlatform\Contracts\OhlcIteratorInterface;
use EmpiricaPlatform\Terminal\Event\ConsoleCommandEvent;
use EmpiricaPlatform\Terminal\Event\ConsoleTerminateEvent;
use EmpiricaPlatform\Terminal\Event\DataEvent;
use EmpiricaPlatform\Terminal\Event\EndEvent;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AutoconfigureTag('kernel.event_listener', ['method' => 'onCommand', 'event' => ConsoleCommandEvent::class])]
class OhlcHistoryDataEmitter
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
            $this->dispatcher->dispatch(new DataEvent($ohlc));
        }
        $this->dispatcher->dispatch(new EndEvent());
    }
}