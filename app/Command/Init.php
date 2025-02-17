<?php declare(strict_types=1);

namespace Arpegx\Bacup\Command;

use Arpgex\Bacup\Model\Configuration;

class Init
{
    public static $middleware = [
        "no_init",
    ];

    public static function handle(array $argv)
    {
        Configuration::getInstance()->create();
        print "Configuration established\n";
    }
}