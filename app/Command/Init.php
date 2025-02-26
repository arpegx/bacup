<?php declare(strict_types=1);

namespace Arpegx\Bacup\Command;

use Arpegx\Bacup\Routing\Rules;
use Arpgex\Bacup\Model\Configuration;

class Init extends Command
{
    /**
     *. defines middleware
     * @var array
     */
    #[\Override]
    protected static array $middleware = [
        Rules::NO_INIT,
    ];

    /**
     *. initialize configuration
     * @param array $argv
     * @return void
     */
    #[\Override]
    public static function handle(array $argv)
    {
        Configuration::getInstance()->create()->save();
        print "Configuration established\n";
    }
}