<?php

/** @suppress PHP0406 */ // Argument '1' passed to with() is expected to be of type array, string given

declare(strict_types=1);

use Arpegx\Bacup\Model\IO;

/** @formatter:off */ 
dataset("views", [
"footer" => [[

"identifier" => "footer",

"source" =>
'<div>
	<hr>
	<p class="mx-2">{$version}, <a href="https://github.com/arpegx/bacup">github.com/arpegx/bacup</a></p>
</div>',

"data" =>
[
	"version" => "0.0",
],

"datalize" =>
'<div>
	<hr>
	<p class="mx-2">0.0, <a href="https://github.com/arpegx/bacup">github.com/arpegx/bacup</a></p>
</div>',

"template" =>
'@template("footer")',

]]
]);
/** @formatter:on */

describe("IO", function () {

	//. make --------------------------------------------------------------------------------------
	describe("make", function () {

		test("validation", function ($view) {
			expect(IO::make($view["identifier"], $view["data"]))->toEqual($view["datalize"]);
		})->with("views");
	});

	//. source ------------------------------------------------------------------------------------
	describe("source", function () {

		test("validation", function ($view) {
			expect(reflect(IO::class, invoke: ["source", [$view["identifier"]]]))
				->toEqual($view["source"]);
		})->with("views");
	});

	//. template ----------------------------------------------------------------------------------
	describe("template", function () {

		test("validation", function ($view) {
			expect(reflect(IO::class, invoke: ["template", [$view["template"]]]))
				->toEqual($view["source"]);
		})->with("views");
	});

	//. datalize ----------------------------------------------------------------------------------
	describe("datalize", function () {

		test("validation", function ($view) {
			$result = reflect(
				IO::class,
				invoke: ["datalize", [$view["source"], $view["data"]]]
			);
			expect($result)->toEqual($view["datalize"]);
		})->with("views");
	});
});
