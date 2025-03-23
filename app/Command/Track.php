<?php declare(strict_types=1);

namespace Arpegx\Bacup\Command;

use Arpegx\Bacup\Routing\Rules;
use Arpgex\Bacup\Model\Configuration;
use Webmozart\Assert\Assert;

use function Arpegx\Bacup\Helper\validate;
use function Laravel\Prompts\form;
use function Laravel\Prompts\info;
use function Laravel\Prompts\note;
use function Laravel\Prompts\outro;

class Track extends Command
{
    /**
     *. defines middleware
     * @var array
     */
    #[\Override]
    protected static array $middleware = [
        Rules::INIT,
    ];

    protected static array $parameter = [
        "target",
        "encrypt",
    ];

    /**
     *. track files
     * @param array $argv
     * @return void
     */
    #[\Override]
    public static function handle(array $argv)
    {
        //. input handling --------------------------------------------------------------------------------------------------
        $input = array();

        if(empty($argv)){
            //. manual user input
            note("Tracking");
            
            $input = form()
            ->text(
                "File/Directory:",
                required: true,
                transform: fn($value) => realpath($value),
                validate:  fn($value) => Rules::exists($value)["result"] ? null : "Source {$value} doesnt exists",
                name: "target"
                )
            ->confirm("Confirm tracking ?", name: "confirm")
            ->submit();
            
            if(!$input["confirm"]){ 
                outro("Tracking canceled."); 
                return;
            }

        } else {
            //. scriptable call
            $input = self::resolve($argv);
        }

        //. Validation ------------------------------------------------------------------------------------------
        validate($input, [
            "target" => Rules::EXISTS,
        ]);
        
        //. do the thing ------------------------------------------------------------------------------------------
        Configuration::getInstance()
            ->add($input)
            ->save();

        //. outro --------------------------------------------------------------------------------------------------------------------
        outro("{$input["target"]} successfully added.");
    }

    public static function resolve($argv){
            $params = array();

            // substrings
            array_walk($argv, function($value) use (&$params){
                Assert::contains($value, "=", "Illegal format.");
                $param = explode("=",$value);
                $params[$param[0]] = $param[1];
            });

            // transform
            Assert::keyExists($params, "target", "Target missing");
            $params["target"] = realpath($params["target"]);

            return $params;
    }
}