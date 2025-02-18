<?php declare(strict_types=1);

namespace Arpegx\Bacup\Command;

class Track extends Command
{
    #[Override]
    protected static array $middleware = [
        "init",
    ];

    #[Override]
    public static function handle(array $argv)
    {
        print __METHOD__ . PHP_EOL;
    }
}