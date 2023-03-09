<?php

/** @var ContainerBuilder $container */

use EmpiricaPlatform\Plot\HistoryData\OhlcList;

$container->register('plot_list', OhlcList::class)
    ->setFactory([OhlcList::class, 'create'])
    ->setArguments([0])
;