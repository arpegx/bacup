<?php

declare(strict_types=1);

namespace Arpegx\Bacup\Command;

use Arpegx\Bacup\Model\IO;
use Arpegx\Bacup\Routing\Rules;
use Arpgex\Bacup\Model\Configuration;

use function Arpegx\Bacup\Helper\validate;
use function Laravel\Prompts\form;

class Untrack extends Command
{
    /**
     * Summary of middleware
     * @var array
     */
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

        // validate
        validate($input, [
            "target" => [Rules::REQUIRED],
            "confirm" => [Rules::REQUIRED]
        ]);

        // remove
        $item = Configuration::getInstance()->item($input["target"]);
        $item->remove()->save();

        // render
        IO::render("Untrack/result", ["target" => $item->item->childNodes->item(0)->textContent]);
    }

    /**
     *. request manual user input
     * @return array
     */
    private static function request()
    {
        $elements = (Configuration::getInstance())->toArray(["source"]);

        return form()
            ->search(
                "Select to untrack",
                fn($value) => strlen($value) > 0
                    ? preg_grep("[{$value}]", $elements)
                    : $elements,
                required: true,
                name: "target",
                transform: function ($value) use ($elements) {
                    return  gettype($value) === "integer" ? $elements[$value] : $value;
                }
            )
            ->confirm("Confirm untracking ?", name: "confirm")
            ->submit();
    }
}
