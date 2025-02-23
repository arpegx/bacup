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

            $dummy = new Router([$arg[0], $arg[1]]);

            $router = new ReflectionClass(Router::class);
            $resolve = $router->getMethod("resolve");
            $cmd = $router->getProperty("cmd");
            $cmd->setAccessible(true);

            $resolve->invoke($dummy);

            expect($cmd->getValue($dummy))->toEqual($arg[2]);
        }
    });

    describe("middleware", function () {
        test("init fails on no_init", function () {

            $dummy = new Router(["bacup", "init"]);
            $router = new ReflectionClass(Router::class);


            $cmd = $router->getProperty('cmd');
            $cmd->setAccessible(true);
            $cmd->setValue($dummy, Init::class);

            $resolve = $router->getMethod("middleware");
            $resolve->invoke($dummy);

        })->throws(\Exception::class);

        test("init succeeds on no_init", function () {
            system("rm -rf " . $_ENV["HOME"] . "/.config/bacup");

            $dummy = new Router(["bacup", "init"]);
            $router = new ReflectionClass(Router::class);


            $cmd = $router->getProperty('cmd');
            $cmd->setAccessible(true);
            $cmd->setValue($dummy, Init::class);

            $resolve = $router->getMethod("middleware");
            $resolve->invoke($dummy);

        })->throwsNoExceptions();
    });
});