<?php

namespace Encore\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\HelpCommand as BaseHelpCommand;
use Symfony\Component\Console\Input\ArrayInput;

class HelpCommand extends BaseHelpCommand
{
    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        parent::configure();

        $this
            ->setName('encore:help')
            ->setHelp(null)
            ->setHidden(true);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = $input->getArgument('command_name');
        
        if (in_array($command, ['help', 'encore:list'])) {
            return $this->getApplication()->find('encore:list')->run(new ArrayInput([]), $output);
        }

        parent::execute($input, $output);
    }
}