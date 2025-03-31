<?php

declare(strict_types=1);

use Arpegx\Bacup\Routing\Rules;

beforeEach(function () {
    uninitialize();
});

describe("Rules", function () {
    //. assert --------------------------------------------------------------------------------------
    describe("assert", function () {
        test('validation', function () {
            fulfill(Rules::INIT);

            expect(
                fn() => Rules::assert(Rules::EXISTS, ["on" => "undefined"], "on")
            )->toThrow(\Webmozart\Assert\InvalidArgumentException::class);

            expect(
                fn() => Rules::assert(Rules::NO_INIT)
            )->toThrow(\Webmozart\Assert\InvalidArgumentException::class);
        });
    });

    //. init --------------------------------------------------------------------------------------
    describe("init", function () {
        test('validation', function () {
            fulfill(Rules::INIT);

            expect(Rules::init()["result"])->toBeTrue();
        });
    });

    //. no_init -----------------------------------------------------------------------------------
    describe("no_init", function () {
        test('validation', function () {
            fulfill(Rules::NO_INIT);

            expect(Rules::no_init()["result"])->toBeTrue();
        });
    });

    //. exists -----------------------------------------------------------------------------------
    describe("exists", function () {
        test('validation', function () {
            fulfill(Rules::INIT);

            expect(Rules::exists(["app"], 0)["result"])->toBeTrue();
            expect(Rules::exists(["undefined"], 0)["result"])->toBeFalse();
        });
    });

    //. required -----------------------------------------------------------------------------------
    describe("required", function () {
        test('validation', function () {
            fulfill(Rules::REQUIRED);

            expect(Rules::required(["key" => null], "key")["result"])->toBeTrue();
        });
    });
});
