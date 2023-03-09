<?php

use EmpiricaPlatform\Terminal\Expression\StringExpressionLanguageProvider;
use EmpiricaPlatform\Terminal\Runner\Application;
use EmpiricaPlatform\Terminal\Runner\MainCommand;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcher;

/** @var \Symfony\Component\DependencyInjection\ContainerBuilder $container */


$container->register('input', ArgvInput::class);
$container->register('output', ConsoleOutput::class);

$container->setAlias(Application::class, 'application');
$container->register('application', Application::class)
    ->setPublic(true)
    ->addMethodCall('add', [new Reference('init_command')])
    ->addMethodCall('setDefaultCommand', [$container->getParameter('init_command_name'), true])
    ->addMethodCall('setDispatcher', [new Reference('event_dispatcher')])
    ->addMethodCall('run', [
        new Reference('input'),
        new Reference('output'),
    ])
;
$container->register('init_command', MainCommand::class)
    ->setArgument('$name', $container->getParameter('init_command_name'))
    ->addMethodCall('setDispatcher', [new Reference('event_dispatcher')])
;
$container->register('event_dispatcher', EventDispatcher::class);

