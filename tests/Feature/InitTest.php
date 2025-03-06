<?php declare(strict_types=1);

beforeEach(function () {
    uninitialize();
});

test("Init::handle required", function () {
    exec("./bacup track", $output, $result_code);
    expect($result_code)->toBe(1);
});

test("Init::handle", function () {
    // successful init
    exec("./bacup init");
    expect(file_exists($_ENV["HOME"] . "/.config/bacup/config.xml"))->toBeTrue();

    // repeated call
    exec("./bacup init", $output, $result_code);
    expect($result_code)->toBe(1);
});
