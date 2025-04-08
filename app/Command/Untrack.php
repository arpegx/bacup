<?php

declare(strict_types=1);

namespace Arpegx\Bacup\Command;

use Arpegx\Bacup\Routing\Rules;
use Arpgex\Bacup\Model\Configuration;

use DOMElement;
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

        print_r($input);
    }

    private static function request()
    {
        $configuration = Configuration::getInstance();

        $elements = $configuration->toArray(
            $configuration->xpath->query('bac:item/bac:source'),
            'source'
        );

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
