<?php declare(strict_types=1);
use Arpegx\Bacup\Command\Help;
use Arpegx\Bacup\Command\Init;
use Arpegx\Bacup\Command\Track;
use Arpegx\Bacup\Routing\Router;
use Arpgex\Bacup\Model\Configuration;

beforeEach(function () {
    uninitialize();
});

describe("Router", function () {
    // datasets
    $routes = [
        "default" => [
            "argv" => ["bacup", ""],
            "target" => Help::class,
        ],
        "help" => [
            "argv" => ["bacup", "help"],
            "target" => Help::class,
        ],
        "init" => [
            "argv" => ["bacup", "init"],
            "target" => Init::class,
        ],
        "track" => [
            "argv" => ["bacup", "track"],
            "target" => Track::class,
        ],
    ];

    //. __construct ---------------------------------------------------------------------
    test("__construct", function () {
        expect(new Router)->toBeInstanceOf(Router::class);
    });

    //. handle --------------------------------------------------------------------------
    test("handle", function () use ($routes) { })->skip("Barely testable void fn");

    //. resolve -------------------------------------------------------------------------
    test("resolve", function () use ($routes) {
        foreach ($routes as $route) {

            $result = reflect(
                class: Router::class,
                set: ["params" => $route["argv"]],
                invoke: ["resolve"],
                gets: ["cmd"],
            );

            expect($result["cmd"])->toEqual($route["target"]);
        }
    });

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