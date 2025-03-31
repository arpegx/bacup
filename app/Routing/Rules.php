<?php

declare(strict_types=1);

namespace Arpegx\Bacup\Routing;

use Arpgex\Bacup\Model\Configuration;
use ReflectionClass;
use Webmozart\Assert\Assert;

class Rules
{
    const INIT = "init";
    const NO_INIT = "no_init";
    const EXISTS = "exists";
    const REQUIRED = "required";

    /**
     *. call rule while asserting success
     * @param string $rule
     * @param array $data
     * @param string|int $key
     * @throws \Webmozart\Assert\InvalidArgumentException
     * @return void
     */
    public static function assert(string $rule, array $data = [], string|int $key = 0)
    {
        Assert::inArray(
            $rule,
            (new ReflectionClass(Rules::class))->getConstants()
        );

        extract(
            call_user_func([Rules::class, $rule], $data, $key)
        );

        Assert::true($result, $message);
    }

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

    /**
     *. checks if file exists
     * @param array $data
     * @param string|int $key
     * @return array{message: string, result: bool}
     */
    public static function exists(array $data, string|int $key = 0)
    {
        return [
            "result" => file_exists($data[$key]),
            "message" => "File does not exist",
        ];
    }

    /**
     *. checks if key is mentioned in data
     * @param array $data
     * @param string|int $key
     * @return array{message: string, result: bool}
     */
    public static function required(array $data, string|int $key = 0)
    {
        return [
            "result" => array_key_exists($key, $data),
            "message" => "{$key} is required",
        ];
    }
}
