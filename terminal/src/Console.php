<?php

namespace EmpiricaPlatform\Terminal;

use EmpiricaPlatform\Terminal\Event\ConsoleCommandEvent;
use EmpiricaPlatform\Terminal\Event\ConsoleTerminateEvent;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class Console extends Command
{
    protected ?ContainerBuilder $container;
    protected bool $running = false;

    protected function configure()
    {
        $this
            ->setName($_SERVER['argv'][0])
            ->setHelp('Runner for development, execution and backtesting trading strategies')
            ->addOption('config-file', 'f', InputArgument::OPTIONAL, 'Config yaml file', $this->getProjectDir().'/config.yaml')
            ->addOption('output-dir', 'o', InputArgument::OPTIONAL, 'Output directory', $this->getProjectDir().'/output')
            ->addOption('cache-dir', 'c', InputArgument::OPTIONAL, 'Cache directory', $this->getProjectDir().'/cache')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $configFile = $input->getOption('config-file');
        if (!file_exists($configFile)) {
            throw new InvalidArgumentException(sprintf('Cannot find config file "%s".', $configFile));
        }
        $outputDir = $input->getOption('output-dir');
        if (!file_exists($outputDir)) {
            throw new InvalidArgumentException(sprintf('Cannot find output directory "%s".', $outputDir));
        }
        if (!is_writable($outputDir)) {
            throw new RuntimeException(sprintf('Unable to write in the "%s" directory.', $outputDir));
        }
        $cacheDir = $input->getOption('cache-dir');
        if (!file_exists($cacheDir)) {
            throw new InvalidArgumentException(sprintf('Cannot find cache directory "%s".', $cacheDir));
        }
        if (!is_writable($cacheDir)) {
            throw new RuntimeException(sprintf('Unable to write in the "%s" directory.', $cacheDir));
        }
        $parameters = new ParameterBag([
            'empirica.project_dir' => $this->getProjectDir(),
            'empirica.config_file' => $configFile,
            'empirica.output_dir' => $outputDir,
            'empirica.cache_dir' => $cacheDir,
        ]);
        $this->container = new ContainerBuilder($parameters);
        $this->container->addCompilerPass(new RegisterListenersPass(), PassConfig::TYPE_BEFORE_REMOVING);
        $loader = new YamlFileLoader($this->container, new FileLocator());
        $loader->load(__DIR__.'/../empirica.yaml');
        $loader->load($configFile);
        $this->container->compile();

        /** @var EventDispatcherInterface $container */
        $dispatcher = $this->container->get('event_dispatcher');
        if (!$dispatcher) {
            throw new RuntimeException('Cannot find "event_dispatcher" service.');
        }
        $dispatcher->dispatch(new ConsoleCommandEvent($this, $input, $output));
        $dispatcher->dispatch(new ConsoleTerminateEvent($this, $input, $output, static::SUCCESS));

        return static::SUCCESS;
    }

    public function run(InputInterface $input = null, OutputInterface $output = null): int
    {
        if ($this->running) {
            return parent::run($input, $output);
        }
        $application = new Application();
        $application->add($this);
        $application->setDefaultCommand($this->getName(), true);
        $this->running = true;
        try {
            $ret = $application->run($input, $output);
        } finally {
            $this->running = false;
        }

        return $ret ?? static::FAILURE;
    }

    public function getProjectDir(): string
    {
        if (!isset($this->projectDir)) {
            $r = new \ReflectionObject($this);
            if (!is_file($dir = $r->getFileName())) {
                throw new \LogicException('Cannot auto-detect project dir.');
            }
            $dir = $rootDir = \dirname($dir);
            while (!is_file($dir.'/composer.lock')) {
                if ($dir === \dirname($dir)) {
                    return $this->projectDir = $rootDir;
                }
                $dir = \dirname($dir);
            }
            $this->projectDir = $dir;
        }

        return $this->projectDir;
    }
}
