<?php

/** @suppress PHP0406 */ // Argument '1' passed to with() is expected to be of type array, string given

declare(strict_types=1);

use Arpegx\Bacup\Command\Help;
use Arpegx\Bacup\Command\Init;
use Arpegx\Bacup\Command\Track;
use Arpegx\Bacup\Routing\Router;

dataset("routes", [
    "default" => [
        "argv" => [0 => "bacup", 1 => ""],
        "target" => Help::class,
        "conditions" => reflect(Help::class, gets: ["middleware"])["middleware"],

    ],
    "help" => [
        "argv" => [0 => "bacup", 1 => "help"],
        "target" => Help::class,
        "conditions" => reflect(Help::class, gets: ["middleware"])["middleware"],

    ],
    "init" => [
        "argv" => [0 => "bacup", 1 => "init"],
        "target" => Init::class,
        "conditions" => reflect(Init::class, gets: ["middleware"])["middleware"],
    ],
    "track" => [
        "argv" => [0 => "bacup", 1 => "track"],
        "target" => Track::class,
        "conditions" => reflect(Track::class, gets: ["middleware"])["middleware"],
    ],
]);

beforeEach(function () {
    uninitialize();
});

describe("Router", function () {
    //. __construct ---------------------------------------------------------------------
    describe("__construct", function () {

        test("__construct", function () {
            expect(new Router)->toBeInstanceOf(Router::class);
        });
    });

    //. handle --------------------------------------------------------------------------
    describe("handle", function () {

        test("validation", function ($argv, $target, $conditions) {

            array_walk($conditions, fn($rule) => fulfill($rule));

            exec("./{$argv[0]} {$argv[0]}", $output, $result_code);
            expect($result_code)->toBe(0);
        })->with("routes");
    });

    //. resolve -------------------------------------------------------------------------
    describe("resolve", function () {

        test("validation", function ($argv, $target, $conditions) {

            $result = reflect(
                class: Router::class,
                invoke: ["resolve", [$argv]],
                gets: ["cmd"],
            );

            expect($result["cmd"])->toEqual($target);
        })->with("routes");
    });

    //. middleware ----------------------------------------------------------------------
    describe("middleware", function () {

        test("fails on invalid conditions", function ($argv, $target, $conditions) {

            if ((bool) $stack = sizeof($conditions)) {
                do {
                    static $fail_on = 0;

                    for ($index = 0; $index < $stack; $index++) {
                        $index == $fail_on
                            ? fail($conditions[$fail_on])
                            : fulfill($conditions[$index]);
                    }

                    expect(
                        fn() =>
                        reflect(
                            class: Router::class,
                            set: ["cmd" => $target],
                            invoke: ["middleware"]
                        )
                    )->toThrow(\Webmozart\Assert\InvalidArgumentException::class);

                    $fail_on++;
                } while ($fail_on < $stack);
            }
        })->with("routes");

        test("succeeds on valid conditions", function ($argv, $target, $conditions) {

            array_walk($conditions, fn($rule) => fulfill($rule));
            reflect(
                class: Router::class,
                set: ["cmd" => $target],
                invoke: ["middleware"]
            );
        })->with("routes")->throwsNoExceptions();
    });
});
