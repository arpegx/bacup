<?php

declare(strict_types=1);

namespace Arpegx\Bacup\Command;

abstract class Command
{
    /**
     *. defines middleware
     * @var array
     */
    protected static array $middleware = array();

    /**
     *. handle command execution
     * @param array $argv
     * @return void
     */
    abstract public static function handle(array $argv);

    /**
     *. getter middleware
     * @return array
     */
    public static function middleware()
    {
        return static::$middleware;
    }
}
