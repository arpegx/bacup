<?php declare(strict_types=1);

namespace Arpegx\Bacup\Routing;

class Router
{
    public static function handle($argv)
    {
        if (count($argv) <= 1) {
            help();
            exit(0);
        }

        try {
            $command = 'Arpegx\Bacup\Command\\' . ucfirst($argv[1]);

            self::middleware($command::middleware());
            $command::handle($argv);

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