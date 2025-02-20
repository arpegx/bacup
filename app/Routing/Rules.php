<?php declare(strict_types=1);

namespace Arpegx\Bacup\Routing;

use Arpgex\Bacup\Model\Configuration;

class Rules
{
    /**
     *. checks for configuration to be existent
     * @return array{message: string, result: bool}
     */
    public static function init()
    {
        return [
            "result" => Configuration::getInstance()->exists(),
            "message" => "Configuration does not exists",
        ];
    }

    /**
     *. checks for configuration to be non-existent
     * @return array{message: string, result: bool}
     */
    public static function no_init()
    {
        return [
            "result" => !(self::init()["result"]),
            "message" => "Configuration does already exists",
        ];
    }
}