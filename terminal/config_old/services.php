<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use EmpiricaPlatform\Terminal\Runner\Application;

return static function (ContainerConfigurator $containerConfigurator) {

    $containerConfigurator->parameters()
        ->set('mailer.transport', 'sendmail')
    ;

    $services = $containerConfigurator->services();

    $services->set('application', Application::class)
        ->public()
    ;
};
