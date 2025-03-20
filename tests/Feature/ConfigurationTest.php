<?php declare(strict_types=1);
use Arpgex\Bacup\Model\Configuration;

beforeEach(function () {
    uninitialize();
});

describe("Configuration", function () {

    //. getInstance -------------------------------------------------------------------------------
    describe("getInstance", function(){

        test("validation", function () {
            expect(Configuration::getInstance())->toBeInstanceOf(Configuration::class);
        });

    });

    //. exists ------------------------------------------------------------------------------------
    describe("exists", function(){

        test("validation", function () {
            expect(Configuration::getInstance()->exists())->toBeFalse();
            
            fulfill("init");
            expect(Configuration::getInstance()->exists())->toBeTrue();
        });

    });
    
    //. create ------------------------------------------------------------------------------------
    describe("create", function(){

        test("validation", function () {
            Configuration::getInstance()->create();
            expect(file_exists($_ENV["HOME"] . "/.config/bacup"))->toBeTrue();
        });

    });

    //. save --------------------------------------------------------------------------------------
    describe("save", function(){

        test("validation", function () {
            Configuration::getInstance()->create()->save();
            expect(file_exists($_ENV["HOME"] . "/.config/bacup/config.xml"))->toBeTrue();
        });
        
    });
});