<?php

declare(strict_types=1);

use Arpegx\Bacup\Command\Track;
use Arpegx\Bacup\Routing\Rules;

beforeEach(function () {
    uninitialize();
});

describe("Track", function () {

    //. handle ------------------------------------------------------------------------------------
    describe("handle", function () {
        test("Architecture: Track::handle fn exists", function () {
            expect('Arpegx\Bacup\Command\Track')->toHaveMethod('handle');
        });

        test('Validaton', function () {
            fulfill(Rules::INIT);

            // stdout isnt quiet catchable, therefore this semi-finished solution
            exec("./bacup track target=app", $output, $result);
            expect(implode($output))->toContain("/usr/src/bacup/app added");
        });
    });

    //. resolve -----------------------------------------------------------------------------------
    describe("resolve", function () {
        test("Validation", function () {
            fulfill(Rules::INIT);

            expect(
                reflect(
                    Track::class,
                    invoke: ["resolve", [["target=app"]]]
                )
            )->toEqual(["confirm" => "true", "target" => "/usr/src/bacup/app"]);

            expect(
                fn() =>
                reflect(
                    Track::class,
                    invoke: ["resolve", [["target=app="]]]
                )
            )->toThrow(\Webmozart\Assert\InvalidArgumentException::class);

            expect(
                fn() =>
                reflect(
                    Track::class,
                    invoke: ["resolve", [["undefined=app"]]]
                )
            )->toThrow(\Exception::class);
        });
    });
});
