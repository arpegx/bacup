<?php declare(strict_types=1);

namespace Arpegx\Bacup\Helper;

use Arpegx\Bacup\Routing\Rules;

function validate(array $data, array $rules){
    foreach($data as $data_key => $data_value){
        return $result = call_user_func([Rules::class, $rules[$data_key]], $data_value);
    }
}