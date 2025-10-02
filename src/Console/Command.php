<?php

namespace Chaos\Core\Console;

abstract class Command
{
    abstract public function getName(): string;

    public function getDescription(): string
    {
        return '';
    }

    abstract public function handle(array $args = []): void;
}
