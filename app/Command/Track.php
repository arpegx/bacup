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
        $input = empty($argv)
            ? self::request()
            : self::resolve($argv);

        validate($input, [
            "target" =>     [Rules::REQUIRED, Rules::EXISTS],
            "confirm" =>    [Rules::REQUIRED],
        ]);

        Configuration::getInstance()
            ->add($input)
            ->save();

        IO::render("Track/result", $input);
    }

    /**
     *. request manual user input
     * @return array
     */
    public static function request()
    {
        return form()
            ->text(
                "File/Directory:",
                required: true,
                transform: fn($value) => realpath($value),
                validate: fn($value) => Rules::exists([$value], 0)["result"] ? null : "Source {$value} doesnt exists",
                name: "target"
            )
            ->confirm("Confirm tracking ?", name: "confirm")
            ->submit();
    }

    /**
     *. resolve commandline parameters
     * @param mixed $argv
     * @throws \Exception
     * @throws \Webmozart\Assert\InvalidArgumentException
     * @return array<bool|string>
     */
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
