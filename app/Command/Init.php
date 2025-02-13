<?php declare(strict_types=1);

namespace Arpegx\Bacup\Command;

use Arpgex\Bacup\Model\Configuration;

class Init
{
    public static function handle(array $argv)
    {
        Configuration::getInstance()->create();
    }
}