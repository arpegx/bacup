<?php declare(strict_types=1);

namespace Arpegx\Bacup\Command;

abstract class Command
{
    protected static array $middleware = array();

    abstract public static function handle(array $argv);

    public static function middleware()
    {
        return static::$middleware;
    }
}