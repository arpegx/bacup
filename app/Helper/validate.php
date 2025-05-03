<?php

declare(strict_types=1);

namespace Arpegx\Bacup\Helper;

use Arpegx\Bacup\Routing\Rules;

if (!function_exists(__NAMESPACE__ . '\validate')) {
    /**
     * Summary of Arpegx\Bacup\Helper\validate
     * @param array $data
     * @param array $subjects
     * @return void
     */
    function validate(array $data, array $subjects)
    {
        foreach ($subjects as $subject => $rules) {
            foreach ($rules as $rule) {
                Rules::assert($rule, $data, $subject);
            }
        }
    }
}
