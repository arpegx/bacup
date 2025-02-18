<?php declare(strict_types=1);

namespace Arpegx\Bacup\Command;

use Arpgex\Bacup\Model\Configuration;

class Init extends Command
{
    #[Override]
    protected static array $middleware = [
        "no_init",
    ];

    #[Override]
    public static function handle(array $argv)
    {
        Configuration::getInstance()->create();
        print "Configuration established\n";
    }
}