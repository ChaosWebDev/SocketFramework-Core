<?php

namespace Chaos\Core\Console;

use Chaos\Core\Console\Commands;
use Chaos\Dotenv\Dotenv;

class Kernel
{
    protected array $commands = [];

    public function __construct()
    {
        // Load .env once here
        $root = getcwd();
        if (file_exists($root . '/.env')) {
            new Dotenv($root);
        }
        
        $files = glob(__DIR__ . "/commands/*.php");

        foreach ($files as $file) {
            $class = pathinfo($file, PATHINFO_FILENAME);

            // Build the FQCN (namespace + class name)
            $fqcn = "Chaos\\Core\\Console\\Commands\\{$class}";

            if (class_exists($fqcn)) {
                $this->commands[] = $fqcn;
            }
        }
    }

    public function run(): void
    {
        global $argv;

        $commandName = $argv[1] ?? 'help';

        foreach ($this->commands as $commandClass) {
            $command = new $commandClass();

            if ($command->getName() === $commandName) {
                $command->handle(array_slice($argv, 2));
                return;
            }
        }

        echo "Unknown command: {$commandName}\n";
    }

    public function getCommands()
    {
        return $this->commands;
    }
}