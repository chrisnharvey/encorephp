<?php

namespace Encore\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DescriptorHelper;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;

class HelpCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected $command = null;

    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this
            ->setName('help')
            ->setDefinition([
                new InputArgument('command_name', InputArgument::OPTIONAL, 'The command name', 'help')
            ])
            ->setDescription('Displays help for a command')
            ->setHidden(true);
    }

    /**
     * {@inheritdoc}
     */
    public function setCommand(Command $command)
    {
        $this->command = $command;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->command === null) {
            $this->command = $this->getApplication()->find($input->getArgument('command_name'));
        }

        if (in_array($this->command->getName(), ['encore:list'])) {
            return $this->getApplication()->find('encore:list')->run(new ArrayInput([]), $output);
        }

        $helper = new DescriptorHelper();
        $helper->describe($output, $this->command);

        $this->command = null;
    }
}