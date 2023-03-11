<?php

namespace EmpiricaPlatform\Terminal;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;

class Command extends BaseCommand
{
    protected ?ContainerBuilder $container;
    protected bool $running = false;

    protected function configure()
    {
        $this
            ->setName($_SERVER['argv'][0])
            ->setHelp('This command allows you to create a user...')
            ->addOption('config-file', 'f', InputArgument::OPTIONAL, 'Config file', $this->getProjectDir().'/config.yaml')
            ->addOption('output-dir', 'o', InputArgument::OPTIONAL, 'Output directory', $this->getProjectDir().'/output')
            ->addOption('cache-dir', 'c', InputArgument::OPTIONAL, 'Cache directory', $this->getProjectDir().'/cache')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $configFile = $input->getOption('config-file');
        if (!file_exists($configFile)) {
            throw new \InvalidArgumentException(sprintf('Cannot find config file "%s".', $configFile));
        }

        $parameters = new ParameterBag([
            'empirica.project_dir' => $this->getProjectDir(),
            'empirica.config_file' => $configFile,
            'empirica.output_dir' => $input->getOption('output-dir'),
            'empirica.cache_dir' => $input->getOption('cache-dir'),
        ]);
        $this->container = new ContainerBuilder($parameters);
        $this->container->addCompilerPass(new RegisterListenersPass(), PassConfig::TYPE_BEFORE_REMOVING);
        $loader = new YamlFileLoader($this->container, new FileLocator());
        $loader->load(__DIR__.'/../empirica.yaml');
        $loader->load($configFile);
        $this->container->compile();

        $output->writeln([
            '1',
            '2',
            $this->container->getParameter('empirica.project_dir'),
        ]);

        return Command::SUCCESS;
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

        return $ret ?? 1;
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
