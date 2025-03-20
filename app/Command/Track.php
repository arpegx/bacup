<?php declare(strict_types=1);

namespace Arpegx\Bacup\Command;

use Arpegx\Bacup\Routing\Rules;
use Arpgex\Bacup\Model\Configuration;

use function Laravel\Prompts\form;

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
        $input = form()
            ->text(
                "File/Directory:",
                required: true,
                transform: fn($value) => realpath($value),
                validate:  fn($value) => file_exists($value) ? null : "Source {$value} doesnt exists",
                name: "path"
                )
            ->confirm("Confirm tracking ?", name: "confirm")
            ->submit();

        // print_r($input);
            
        if($input["confirm"]){

            Configuration::getInstance()
                ->add($input)
                ->save();
        }

        // print(file_get_contents($_ENV["HOME"]."/.config/bacup/config.xml"));
    }
}