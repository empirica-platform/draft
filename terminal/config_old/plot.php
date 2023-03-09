<?php

use EmpiricaPlatform\Plot\DrawImage\DrawCanvas;
use EmpiricaPlatform\Plot\HistoryData\OhlcList;
use EmpiricaPlatform\Terminal\Event\DataEvent;
use EmpiricaPlatform\Terminal\Event\EndEvent;
use EmpiricaPlatform\Terminal\Plot;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

error_reporting(E_ALL ^ E_DEPRECATED ^ E_WARNING);

/** @var ContainerBuilder $container */

$container->setParameter('plot_file', '%empirica.project_dir%/qwe3.png');
$container->setParameter('plot_font', '%empirica.project_dir%/vendor/zkrat/draw-ohlc/src/font/Hack-Regular.ttf');


$container->register('plot_canvas', DrawCanvas::class)
    ->setFactory([DrawCanvas::class, 'createCanvas'])
    ->setArguments([1000, 600])
    ->addMethodCall('setFontPath', [$container->getParameter('plot_font')])
    ->addMethodCall('setFontSize', [10])
;
$container->register('plot', Plot::class)
    ->setArgument('$csvOhlc', new Reference('plot_list'))
    ->setArgument('$canvas', new Reference('plot_canvas'))
    ->setArgument('$file', $container->getParameter('plot_file'))
    ->addTag('kernel.event_listener', [
        'event' => DataEvent::class,
        'method' => 'addOhlc',
    ])
    ->addTag('kernel.event_listener', [
        'event' => EndEvent::class,
        'method' => 'renderFile',
    ])
;
