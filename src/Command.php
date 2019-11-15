<?php

namespace Encore;

use Symfony\Component\Console\Command\Command as BaseCommand;

class Command extends BaseCommand
{
    /**
     * Bootstrap the command
     *
     * @return void
     */
    public function bootstrap()
    {
        parent::__construct();
    }
}