<?php declare(strict_types=1);

namespace Arpegx\Bacup\Command;

class Help extends Command
{
    public static function handle(array $argv)
    {
        print __METHOD__ . PHP_EOL;
    }
}