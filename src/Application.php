<?php

namespace Encore;

use Encore\Command\HelpCommand;
use Encore\Command\ListCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\CommandLoader\FactoryCommandLoader;

abstract class Application extends BaseApplication
{
    /**
     * The current EncorePHP version
     * 
     * @var string
     */
    public const ENCORE_VERSION = 'dev';

    /**
     * The application name
     *
     * @var string
     */
    protected $name = 'UNKNOWN';

    /**
     * Bootstrap the application
     *
     * @return void
     */
    public function bootstrap()
    {
        $this->setDefaultCommand('encore:list');

        $this->setName($this->name);

        $this->container = $this->container ?? new Container();
    }

    /**
     * Returns a list of the applications command
     * names and the class they resolve to.
     *
     * @return array
     */
    abstract public function commands(): array;

    /**
     * {@inheritdoc}
     */
    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        $commandLoader = new FactoryCommandLoader(
            iterator_to_array($this->wrapCommands())
        );

        $this->setCommandLoader($commandLoader);

        return parent::run($input, $output);
    }

    /**
     * Wraps commands from commands() method in closures
     * so they can be lazily loaded.
     *
     * @return Generator
     */
    protected function wrapCommands()
    {
        foreach ($this->commands() as $name => $command) {
            $command = $this->wrapCommand($command);

            yield $name => $command;
        }
    }

    /**
     * Wrap command in a closure so we can lazily load it
     * from the container.
     *
     * @param string $command
     * @return void
     */
    protected function wrapCommand(string $command)
    {
        return function () use ($command) {
            $command = $this->container->get($command);
            $command->bootstrap();

            return $command;
        };
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultInputDefinition()
    {
        return new InputDefinition([
            new InputArgument('command', InputArgument::REQUIRED, 'The command to execute'),
            new InputOption('--help', '-h', InputOption::VALUE_NONE, 'Display this help message'),
            new InputOption('--quiet', '-q', InputOption::VALUE_NONE, 'Do not output any message'),
            new InputOption('--version', '-v', InputOption::VALUE_NONE, 'Display this application version'),
            new InputOption('--no-interaction', '-n', InputOption::VALUE_NONE, 'Do not ask any interactive question'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultCommands()
    {
        return [new ListCommand, new HelpCommand];
    }

}
