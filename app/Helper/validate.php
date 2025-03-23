<?php declare(strict_types=1);

namespace Arpegx\Bacup\Helper;

use Arpegx\Bacup\Routing\Rules;
use Webmozart\Assert\Assert;

function validate(array $data, array $rules){
    foreach($data as $data_key => $data_value){
        if(array_key_exists($data_key, $rules)){
            
            $result = call_user_func([Rules::class, $rules[$data_key]], $data_value);

            Assert::true($result["result"], $result["message"]);
        }
    }
}