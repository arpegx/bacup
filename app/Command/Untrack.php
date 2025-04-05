<?php

declare(strict_types=1);

namespace Arpegx\Bacup\Command;

use Arpegx\Bacup\Routing\Rules;
use function Laravel\Prompts\form;

class Untrack extends Command
{
    /**
     * Summary of middleware
     * @var array
     */
    #[\Override]
    protected static array $middleware = [
        Rules::INIT,
    ];

    /**
     * Summary of handle
     * @param array $argv
     * @return void
     */
    #[\Override]
    public static function handle(array $argv)
    {
        $input =  self::request();
    }

    private static function request()
    {
        $elements = ["x", "y"];

        return form()
            ->search(
                "Select to untrack",
                fn($value) => strlen($value) > 0
                    ? preg_grep("[{$value}]", $elements)
                    : $elements,
                required: true,
            )
            ->confirm("Confirm untracking ?", name: "confirm")
            ->submit();
    }
}
