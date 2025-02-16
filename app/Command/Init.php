<?php declare(strict_types=1);

namespace Arpegx\Bacup\Command;

use Arpgex\Bacup\Model\Configuration;

class Init
{
    public static function handle(array $argv)
    {
        //repeated call
        if (Configuration::getInstance()->exists()) {
            print "Configuration exists already\n";
            return 0;
        }

        try {
            Configuration::getInstance()->create();
            print "Configuration established\n";

        } catch (\Exception $e) {
            print $e->getMessage();
        }

    }
}