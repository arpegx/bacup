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
        "conditions" => [fn() => null],
    ],
    "help" => [
        "argv" => ["app" => "bacup", "command" => "help"],
        "target" => Help::class,
        "conditions" => [fn() => null],
    ],
    "init" => [
        "argv" => ["app" => "bacup", "command" => "init"],
        "target" => Init::class,
        "conditions" => ["no_init" => fn() => uninitialize()],
    ],
    "track" => [
        "argv" => ["app" => "bacup", "command" => "track"],
        "target" => Track::class,
        "conditions" => ["init" => fn() => Configuration::getInstance()->create()->save()],
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
    test("handle", function ($argv, $target, $conditions) {
        array_walk($conditions, fn($condition) => call_user_func($condition));

        exec("./{$argv["app"]} {$argv["command"]}", $output, $result_code);
        expect($result_code)->toBe(0);

    })->with("routes");

    //. resolve -------------------------------------------------------------------------
    test("resolve", function ($argv, $target, $conditions) {
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

        test("succeeds on valid conditions", function ($argv, $target, $conditions) {

            array_walk($conditions, fn($condition) => call_user_func($condition));

            reflect(
                class: Router::class,
                set: ["cmd" => $target],
                invoke: ["middleware"]
            );
        })->with("routes")->throwsNoExceptions();
    });

    //. execute -------------------------------------------------------------------------
    test("execute", function () { })->skip(message: "Barely testable void fn");
});