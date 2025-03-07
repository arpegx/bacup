<?php declare(strict_types=1);

namespace Arpegx\Bacup\Command;

use Arpegx\Bacup\Model\View;
use function Termwind\render;

class Help extends Command
{
    /**
     *. display help informations
     * @param array $argv
     * @return void
     */
    #[\Override]
    public static function handle(array $argv)
    {
        render(
            View::make("help", [
                "test" => "templated string",
                "version" => "YY.mm.release",
            ])
        );
    }
}