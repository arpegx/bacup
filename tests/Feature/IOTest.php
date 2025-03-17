<?php declare(strict_types=1);

use Arpegx\Bacup\Model\IO;

describe("IO", function () {

    //. make --------------------------------------------------------------------------------------
    describe("make", function () {
        test("successful", function () {
            expect(IO::make("footer", ["version" => "0.0"]))->toEqual(
                "<div>
    <hr>
    <p class=\"mx-2\">0.0, <a href=\"https://github.com/arpegx/bacup\">github.com/arpegx/bacup</a> </p>
</div>"
            );
        });
    });

    //. source ------------------------------------------------------------------------------------
    describe("source", function () {
        test("successful", function () {
            expect(reflect(IO::class, invoke: ["source", ["help"]]))
                ->toEqual(file_get_contents("./app/View/help.html"));
        });
    });

    //. template ----------------------------------------------------------------------------------
    describe("template", function () {
        test("successful", function () {
            expect(reflect(IO::class, invoke: ["template", ['@template("footer")']]))
                ->toEqual("<div>
    <hr>
    <p class=\"mx-2\">{\$version}, <a href=\"https://github.com/arpegx/bacup\">github.com/arpegx/bacup</a> </p>
</div>");
        });
    });

    //. datalize ----------------------------------------------------------------------------------
    describe("datalize", function () {
        test("successful", function () {
            $result = reflect(
                IO::class,
                invoke: ["datalize", [file_get_contents("./app/View/footer.html"), ["version" => "0.0"]]]
            );
            expect($result)->toEqual("<div>
    <hr>
    <p class=\"mx-2\">0.0, <a href=\"https://github.com/arpegx/bacup\">github.com/arpegx/bacup</a> </p>
</div>"
            );
        });
    });
});
