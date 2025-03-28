<?php

declare(strict_types=1);

namespace Arpegx\Bacup\Helper;

use Arpegx\Bacup\Routing\Rules;
use ReflectionClass;
use Webmozart\Assert\Assert;

if (!function_exists(__NAMESPACE__ . '\validate')) {
    function validate(array $data, array $subjects)
    {
        $checkables = (new ReflectionClass(Rules::class))->getConstants();

        foreach ($subjects as $subject => $rules) {
            foreach ($rules as $rule) {

                Assert::inArray($rule, $checkables);

                $result = call_user_func([Rules::class, $rule], $data, $subject);
                Assert::true($result["result"], $result["message"]);
            }
        }
    }
}
