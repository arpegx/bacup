<?php declare(strict_types=1);
use Arpegx\Bacup\Command\Help;
use Arpegx\Bacup\Command\Init;
use Arpegx\Bacup\Command\Track;
use Arpegx\Bacup\Routing\Router;
use Arpgex\Bacup\Model\Configuration;

dataset("routes", [
    "default" => [
        "argv" => ["app" => "bacup", "command" => ""],
        "target" => Help::class,
        "condition" => [fn() => null],
    ],
    "help" => [
        "argv" => ["app" => "bacup", "command" => "help"],
        "target" => Help::class,
        "condition" => [fn() => null],
    ],
    "init" => [
        "argv" => ["app" => "bacup", "command" => "init"],
        "target" => Init::class,
        "condition" => [fn() => null],
    ],
    "track" => [
        "argv" => ["app" => "bacup", "command" => "track"],
        "target" => Track::class,
        "condition" => [fn() => Configuration::getInstance()->create()->save()],
    ],
]);

beforeEach(function () {
    uninitialize();
});

describe("Router", function () {
    //. __construct ---------------------------------------------------------------------
    test("__construct", function () {
        expect(new Router)->toBeInstanceOf(Router::class);
    });

    //. handle --------------------------------------------------------------------------
    test("handle", function ($argv, $target, $condition) {
        call_user_func(...$condition);

        exec("./{$argv["app"]} {$argv["command"]}", $output, $result_code);
        expect($result_code)->toBe(0);

    })->with("routes");

    //. resolve -------------------------------------------------------------------------
    test("resolve", function ($argv, $target, $condition) {
        $result = reflect(
            class: Router::class,
            set: ["params" => $argv],
            invoke: ["resolve"],
            gets: ["cmd"],
        );

        expect($result["cmd"])->toEqual($target);

    })->with("routes");

    //. middleware ----------------------------------------------------------------------
    describe("middleware", function () {
        test("init fails on no_init", function () {

            Configuration::getInstance()->create()->save();

            reflect(
                class: Router::class,
                set: ["cmd" => Init::class],
                invoke: ["middleware"]
            );
        })->throws(\Exception::class);

        test("init succeeds on no_init", function () {

            reflect(
                class: Router::class,
                set: ["cmd" => Init::class],
                invoke: ["middleware"]
            );
        })->throwsNoExceptions();
    });

    //. execute -------------------------------------------------------------------------
    test("execute", function () { })->skip(message: "Barely testable void fn");
});