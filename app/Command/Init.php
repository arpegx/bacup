<?php declare(strict_types=1);

namespace Arpegx\Bacup\Command;

class Init
{
    public static function handle(array $argv)
    {
        print __METHOD__ . PHP_EOL;

        // variables
        $config_dir = getenv("HOME") . "/.config/bacup";
        $config = $config_dir . "/config.xml";
        $default = file_get_contents("data/default.xml");

        // repeated call
        if (file_exists($config)) {
            print "Configuration exists already\n";
            return 0;
        }

        // setup configuration
        mkdir($config_dir, 0700, true);
        file_put_contents($config, $default);

        print "Configuration established\n";
    }
}