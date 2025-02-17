<?php declare(strict_types=1);

namespace Arpegx\Bacup\Routing;

use Arpegx\Bacup\Command\Init;
use Arpegx\Bacup\Command\Track;

class Router
{
    public static function handle($argv)
    {
        if (count($argv) <= 1) {
            help();
            exit(0);
        }

        try {
            switch ($argv[1]) {
                case "init":
                    self::middleware(Init::$middleware);
                    Init::handle($argv);
                case "track":
                    self::middleware(Track::$middleware);
                    Track::handle($argv);
                    break;
                default:
                    help();
            }
        } catch (\Exception $e) {
            print $e->getMessage();
            exit(1);
        }
    }

    public static function middleware(array $middlewares)
    {
        foreach ($middlewares as $middleware) {
            call_user_func([Rules::class, $middleware]) ?: throw new \Exception("Rule {$middleware} failed");
        }
    }
}

function help()
{
    print "Help Message\n";
}