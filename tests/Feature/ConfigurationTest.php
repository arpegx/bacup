<?php declare(strict_types=1);
use Arpgex\Bacup\Model\Configuration;

describe("Configuration", function () {

    test("Configuration::getInstance", function () {
        expect(Configuration::getInstance())->toBeInstanceOf(Configuration::class);
    });

    test("Configuration::exists", function () {
        expect(Configuration::getInstance()->exists())->toBe(file_exists($_ENV["HOME"] . "/.config/bacup/config.xml"));
    });

    test("Configuration::create", function () {
        Configuration::getInstance()->create()->save();
        expect(Configuration::getInstance()->exists())->toBe(file_exists($_ENV["HOME"] . "/.config/bacup/config.xml"));
    });
});