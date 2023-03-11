<?php

namespace EmpiricaPlatform\Terminal\EventListener;

use EmpiricaPlatform\Terminal\Event\ConsoleCommandEvent;
use EmpiricaPlatform\Terminal\Event\ConsoleTerminateEvent;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('kernel.event_listener', ['method' => 'onCommand', 'event' => ConsoleCommandEvent::class])]
#[AutoconfigureTag('kernel.event_listener', ['method' => 'onTerminate', 'event' => ConsoleTerminateEvent::class])]
class ConsoleListener
{
    public function onCommand(ConsoleCommandEvent $event): void
    {
        var_dump(111);
    }

    public function onTerminate(ConsoleTerminateEvent $event): void
    {
        var_dump(222);
    }
}
