<?php declare(strict_types=1);

namespace Arpegx\Bacup\Routing;

use Arpgex\Bacup\Model\Configuration;

class Rules
{
    const INIT = "init";
    const NO_INIT = "no_init";

    /**
     *. checks for configuration to be existent
     * @return array{result: bool, message: string}
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
     * @return array{result: bool, message: string}
     */
    public static function no_init()
    {
        return [
            "result" => !(self::init()["result"]),
            "message" => "Configuration does already exists",
        ];
    }
}