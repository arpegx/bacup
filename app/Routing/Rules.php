<?php declare(strict_types=1);

namespace Arpegx\Bacup\Routing;

use Arpgex\Bacup\Model\Configuration;

class Rules
{
    public static function init()
    {
        return Configuration::getInstance()->exists();
    }
    public static function no_init()
    {
        return !self::init();
    }
}