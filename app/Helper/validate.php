<?php

declare(strict_types=1);

namespace Arpegx\Bacup\Helper;

use Arpegx\Bacup\Routing\Rules;
use Webmozart\Assert\Assert;

function validate(array $data, array $rules)
{
    foreach ($rules as $rule_key => $rule_values) {
        for ($i = 0; $i < sizeof($rule_values); $i++) {
            $result = array();
            if ($rule_values[$i] == Rules::REQUIRED) {
                $result = call_user_func([Rules::class, $rule_values[$i]], $rule_key, $data);
            } else {
                $result = call_user_func([Rules::class, $rule_values[$i]], $data[$rule_key]);
            }
            Assert::true($result["result"], $result["message"]);
        }
    }
}
