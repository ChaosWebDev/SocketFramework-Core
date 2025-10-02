<?php

namespace Chaos\Core\Console\Commands;

use Chaos\Core\Console\Command;
use Chaos\Core\Console\Kernel;

class Help extends Command
{
    public function getName(): string
    {
        return 'help';
    }

    public function getDescription(): string
    {
        return 'Display available commands';
    }

    public function handle(array $args = []): void
    {
        // Access Kernel’s registered commands
        $kernel = new Kernel();
        $commands = $kernel->getCommands();

        echo "Available commands:\n\n";
        foreach ($commands as $commandClass) {
            $command = new $commandClass();
            printf("  %-20s %s\n", $command->getName(), $command->getDescription());
        }
    }
}
