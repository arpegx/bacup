<?php

declare(strict_types=1);

namespace Arpegx\Bacup\Routing;

use Arpgex\Bacup\Model\Configuration;

class Rules
{
    const INIT = "init";
    const NO_INIT = "no_init";
    const EXISTS = "exists";
    const REQUIRED = "required";

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

    public static function exists(string $file)
    {
        return [
            "result" => file_exists($file),
            "message" => "File does not exist",
        ];
    }

    public static function required(string $key, array $array)
    {
        return [
            "result" => array_key_exists($key, $array),
            "message" => "{$key} is required",
        ];
    }
}
