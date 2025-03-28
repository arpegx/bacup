<?php

declare(strict_types=1);

namespace Arpegx\Bacup\Command;

use Arpegx\Bacup\Model\IO;
use Arpegx\Bacup\Routing\Rules;
use Arpgex\Bacup\Model\Configuration;
use Webmozart\Assert\Assert;
use function Arpegx\Bacup\Helper\validate;
use function Laravel\Prompts\form;

class Track extends Command
{
    /**
     *. defines middleware
     * @var array
     */
    #[\Override]
    protected static array $middleware = [
        Rules::INIT,
    ];

    /**
     *. track files
     * @param array $argv
     * @return void 
     */
    #[\Override]
    public static function handle(array $argv)
    {
        //. input handling ------------------------------------------------------------------------
        $input = empty($argv)
            ? self::request()
            : self::resolve($argv);

        //. Validation ----------------------------------------------------------------------------
        validate($input, [
            "target" =>     [Rules::EXISTS, Rules::REQUIRED],
            "confirm" =>    [Rules::REQUIRED],
        ]);

        //. do the thing --------------------------------------------------------------------------
        Configuration::getInstance()
            ->add($input)
            ->save();

        //. view ---------------------------------------------------------------------------------
        IO::render("Track/result", $input);
    }

    public static function request()
    {
        return form()
            ->text(
                "File/Directory:",
                required: true,
                transform: fn($value) => realpath($value),
                validate: fn($value) => Rules::exists($value)["result"] ? null : "Source {$value} doesnt exists",
                name: "target"
            )
            ->confirm("Confirm tracking ?", name: "confirm")
            ->submit();
    }

    public static function resolve($argv)
    {
        $params["confirm"] = "true";

        foreach ($argv as $arg) {
            switch (true) {
                case str_starts_with($arg, "target"):
                    Assert::regex($arg, '/^target=[^=]*$/');
                    [$parameter, $value] = explode("=", $arg);
                    $params[$parameter] = realpath(trim($value, "\""));
                    break;
                default:
                    throw new \Exception("Unkown parameter");
            };
        }

        return $params;
    }
}
