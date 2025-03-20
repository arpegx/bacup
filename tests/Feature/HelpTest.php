<?php declare(strict_types=1);

describe("help", function(){
    describe("handle", function(){
        test("validation", function($glob){

            // stdout isnt quiet catchable, therefore this semi-finished solution
            exec("./bacup help", $output, $result);
            expect(implode($output))->toContain("Help System");

        });
    });
});