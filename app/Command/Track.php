<?php declare(strict_types=1);

namespace Arpegx\Bacup\Command;

use Arpegx\Bacup\Routing\Rules;
use Arpgex\Bacup\Model\Configuration;

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

    /**
     *. track files
     * @param array $argv
     * @return void
     */
    #[\Override]
    public static function handle(array $argv)
    {
        if(empty($argv)){

            note("Tracking");
            
            $input = form()
            ->text(
                "File/Directory:",
                required: true,
                transform: fn($value) => realpath($value),
                validate:  fn($value) => file_exists($value) ? null : "Source {$value} doesnt exists",
                name: "target"
                )
            ->confirm("Confirm tracking ?", name: "confirm")
            ->submit();
            
            if($input["confirm"]){
                
                Configuration::getInstance()
                ->add($input)
                ->save();
            }
        } else {
            self::resolve($argv);
        }
        
        // info();
        outro("{$input["path"]} successfully added.");
        // print(file_get_contents($_ENV["HOME"]."/.config/bacup/config.xml"));
    }

    public static function resolve($argv){
            $argv = ["target" => $argv[0]];

            // print_r($argv);
            $validated = validate($argv, [
                "target" => Rules::EXISTS,
            ]);

            var_dump($validated);
    }
}