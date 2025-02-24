<?php declare(strict_types=1);
use Arpegx\Bacup\Command\Help;
use Arpegx\Bacup\Command\Init;
use Arpegx\Bacup\Command\Track;
use Arpegx\Bacup\Routing\Router;


describe("Router", function () {

    test("__construct", function () {
        expect(new Router)->toBeInstanceOf(Router::class);
    });

    test("handle", function () {
        $params = ["bacup", ""];

        expect((new Router($params))->handle())->toEqual(Help::handle($params));
    });

    test("resolve", function () {
        $params = [
            ["bacup", "help", Help::class],
            ["bacup", "init", Init::class],
            ["bacup", "track", Track::class],
        ];
        foreach ($params as $arg) {

            $result = reflect(
                class: Router::class,
                set: ["params" => [$arg[0], $arg[1]]],
                invoke: ["resolve"],
                gets: ["cmd"],
            );

            expect($result["cmd"])->toEqual($arg[2]);
        }
    });

    describe("middleware", function () {
        test("init fails on no_init", function () {

            reflect(
                class: Router::class,
                set: ["cmd" => Init::class],
                invoke: ["middleware"]
            );
        })->throws(\Exception::class);

        test("init succeeds on no_init", function () {
            system("rm -rf " . $_ENV["HOME"] . "/.config/bacup");

            reflect(
                class: Router::class,
                set: ["cmd" => Init::class],
                invoke: ["middleware"]
            );
        })->throwsNoExceptions();
    });
});