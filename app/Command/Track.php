<?php declare(strict_types=1);

namespace Arpegx\Bacup\Command;

use Arpegx\Bacup\Routing\Rules;

class Track extends Command
{
    /**
     *. defines middleware
     * @var array
     */
    #[\Override]
    protected static array $middleware = [
        Rules::INIT,
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