<?php

/** @suppress PHP0406 */ // Argument '1' passed to reflect() is expected to be of type string, Arpgex\Bacup\Model\Configuration

declare(strict_types=1);

use Arpegx\Bacup\Routing\Rules;
use Arpgex\Bacup\Model\Configuration;

beforeEach(function () {
    uninitialize();
});

describe("Configuration", function () {

    //. getInstance -------------------------------------------------------------------------------
    describe("getInstance", function () {

        test("validation", function () {
            expect(Configuration::getInstance())->toBeInstanceOf(Configuration::class);
        });
    });

    //. create ------------------------------------------------------------------------------------
    describe("create", function () {

        test("validation", function () {
            Configuration::getInstance()->create();
            expect(file_exists($_ENV["HOME"] . "/.config/bacup"))->toBeTrue();
        });
    });

    //. add ------------------------------------------------------------------------------------
    describe("add", function () {

        test("validation", function () {
            fulfill(Rules::INIT);

            $result = reflect(
                Configuration::getInstance(),
                invoke: ["add", [["target" => "/usr/src/bacup/app"]]],
                gets: ["configuration"]
            );

            expect(($result["configuration"])->textContent)->toEqual("\n/usr/src/bacup/app");
        })->todo("query for specific note");
    });

    //. save --------------------------------------------------------------------------------------
    describe("save", function () {

        test("validation", function () {
            Configuration::getInstance()->create()->save();
            expect(file_exists($_ENV["HOME"] . "/.config/bacup/config.xml"))->toBeTrue();
        });
    });

    //. exists ------------------------------------------------------------------------------------
    describe("exists", function () {

        test("validation", function () {
            expect(Configuration::getInstance()->exists())->toBeFalse();

            fulfill(Rules::INIT);
            expect(Configuration::getInstance()->exists())->toBeTrue();
        });
    });
});
