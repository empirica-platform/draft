<?php

use DrawOHLC\HistoryData\OhlcList;
use EmpiricaPlatform\PeriodicProvider\OhlcIterator;
use EmpiricaPlatform\Terminal\Provider;
use EmpiricaPlatform\Terminal\Event\InitEvent;
use EmpiricaPlatform\Terminal\ValueObject\Symbol;
use EmpiricaPlatform\Terminal\ValueObject\SymbolPair;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\ExpressionLanguage\Expression;

/** @var ContainerBuilder $container */

$container->setParameter('data_source_file', 'https://data.binance.vision/data/spot/daily/klines/BTCUSDT/1m/BTCUSDT-1m-2023-01-05.zip');
$container->setParameter('data_work_dir', '%empirica.project_dir%/var/binance');

$container->register('data_symbol_base', OhlcList::class)
    ->setFactory([Symbol::class, 'from'])
    ->setArguments(['BTC'])
;
$container->register('data_symbol_quote', OhlcList::class)
    ->setFactory([Symbol::class, 'from'])
    ->setArguments(['USDT'])
;
$container->register('data_symbol_pair', SymbolPair::class)
    ->setArgument('$base', new Reference('data_symbol_base'))
    ->setArgument('$quote', new Reference('data_symbol_quote'))
;
$container->register('data_iterator', OhlcIterator::class)
    ->setArgument('$symbolPair', new Reference('data_symbol_pair'))
   // ->setArgument('$sourceFile', $container->getParameter('data_source_file'))
   // ->setArgument('$workDir', $container->getParameter('data_work_dir'))
;
$container->register('data', Provider::class)
    ->setArgument('$dispatcher', new Reference('event_dispatcher'))
    ->setArgument('$iterator', new Reference('data_iterator'))
    ->setArgument('$output', new Reference('output'))
    ->setArgument('$projectDir', new Expression('getProjectDir()'))
    ->addTag('kernel.event_listener', [
        'event' => InitEvent::class,
        'method' => 'emitDataEvent',
    ])
;
