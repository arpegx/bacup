<?php declare(strict_types=1);

namespace Arpegx\Bacup\Command;

class Track extends Command
{
    /**
     *. defines middleware
     * @var array
     */
    #[\Override]
    protected static array $middleware = [
        "init",
    ];

    /**
     *. track files
     * @param array $argv
     * @return void
     */
    #[\Override]
    public static function handle(array $argv)
    {
        print __METHOD__ . PHP_EOL;
    }
}