<?php

namespace EmpiricaPlatform\Terminal;

use EmpiricaPlatform\Contracts\OhlcIteratorInterface;
use EmpiricaPlatform\Terminal\Event\DataEvent;
use EmpiricaPlatform\Terminal\Event\EndEvent;
use EmpiricaPlatform\Terminal\Event\ConsoleCommandEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Provider
{
    public function __construct(
        protected EventDispatcherInterface $dispatcher,
        protected OhlcIteratorInterface $iterator,
        protected OutputInterface $output,
        protected string $projectDir
    )
    {
    }

    public function emitDataEvent(ConsoleCommandEvent $event): void
    {
        $this->output->writeln('projectDir: ' . $this->projectDir);
        foreach ($this->iterator as $ohlc) {
            $this->dispatcher->dispatch(new DataEvent($ohlc));
        }
        $this->dispatcher->dispatch(new EndEvent());
    }
}
