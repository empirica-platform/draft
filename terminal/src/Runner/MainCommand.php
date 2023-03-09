<?php

namespace EmpiricaPlatform\Terminal\Runner;

use EmpiricaPlatform\Terminal\Event\InitEvent;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class MainCommand extends Command
{
    private ?EventDispatcherInterface $dispatcher = null;

    public function setDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('execute command');
        $this->dispatcher->dispatch(new InitEvent());

        return Command::SUCCESS;
    }
}
