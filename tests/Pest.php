<?php

use Arpegx\Bacup\Routing\Rules;
use Arpgex\Bacup\Model\Configuration;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

// pest()->extend(Tests\TestCase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

/**
 * Reflection Helper
 * @param string $class
 * @param array $set
 * @param array $invoke
 * @param array $gets
 * @return array
 */
function reflect(string $class, array $set = [], array $invoke = [], array $gets = [])
{
    $_class = new $class();
    $reflection = new ReflectionClass($class);

    array_walk($set, function ($value, $key) use ($reflection, $_class) {
        $property = $reflection->getProperty($key);
        $property->setAccessible(true);
        $property->setValue($_class, $value);
    });

    array_walk($invoke, function ($value) use ($reflection, $_class) {
        ($reflection->getMethod($value))->invoke($_class);
    });

    $result = array();
    foreach ($gets as $get) {
        $property = $reflection->getProperty($get);
        $property->setAccessible(true);
        $result[$get] = $property->getValue($_class);
    }

    return $result;
}

function uninitialize()
{
    if (file_exists($_ENV["HOME"] . "/.config/bacup")) {
        system("rm -rf " . $_ENV["HOME"] . "/.config/bacup");
    }
}

function fulfill(string $rule)
{
    switch ($rule) {
        case Rules::INIT:
            Configuration::getInstance()->create()->save();
            break;
        case Rules::NO_INIT:
            uninitialize();
            break;
    }
}