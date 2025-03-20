<?php declare(strict_types=1);

namespace Arpegx\Bacup\Command;

use Arpegx\Bacup\Model\IO;

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
        IO::render("help");
    }
}