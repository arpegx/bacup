<?php declare(strict_types=1);

beforeEach(function () {
    uninitialize();
});

describe("Init", function(){

    describe("handle", function(){

        test("required", function () {
            exec("./bacup track", $output, $result_code);
            expect($result_code)->toBe(1);
        });
    });
    
    describe("repeated", function(){

        test("validation", function () {
            // successful init
            exec("./bacup init");
            expect(file_exists($_ENV["HOME"] . "/.config/bacup/config.xml"))->toBeTrue();
            
            // repeated call
            exec("./bacup init", $output, $result_code);
            expect($result_code)->toBe(1);
        });
    });
});