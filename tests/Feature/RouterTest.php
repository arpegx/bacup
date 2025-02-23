<?php declare(strict_types=1);
use Arpegx\Bacup\Routing\Router;

describe("Router", function () {
    test("__construct", function () {
        expect(new Router)->toBeInstanceOf(Router::class);
    });
});