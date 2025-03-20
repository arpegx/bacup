<?php declare(strict_types=1);

namespace Arpegx\Bacup\Command;

use Arpegx\Bacup\Routing\Rules;
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
            ->text("Path: ", required: true)
            ->confirm("Do you want to track this file ?")
            ->submit();
    }
}