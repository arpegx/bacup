<?php

declare(strict_types=1);

namespace Arpegx\Bacup\Routing;

use Arpegx\Bacup\Command\Help;
use Arpegx\Bacup\Command\Init;
use Arpegx\Bacup\Command\Track;

class Router
{
    /**
     * valid routes
     * @var array
     */
    private array $routes = [
        "help" => Help::class,
        "init" => Init::class,
        "track" => Track::class,
    ];

    /**
     *. command to be executed
     * @var string
     */
    private string $cmd = Help::class;

    /**
     *. optional parameters for commands
     * @var array
     */
    private array $params = array();

    /**
     *. handle user input
     * @return void
     */
    public function handle(array $argv)
    {
        $this
            ->resolve($argv)
            ->middleware()
            ->execute();
    }

    /**
     *. resolve command
     * @return static
     */
    private function resolve(array $argv)
    {
        switch (true) {
            // check the command
            case sizeof($argv) >= 2:
                $this->cmd = key_exists($argv[1], $this->routes) ? $this->routes[$argv[1]] : Help::class;
                // extract editional parameters
            case sizeof($argv) >= 3:
                $this->params = array_slice($argv, 2);
                break;
            default:
                $this->cmd = Help::class;
        }

        return $this;
    }

    /**
     *. validate middleware restrictions
     * @throws \Webmozart\Assert\InvalidArgumentException
     * @return static
     */
    private function middleware()
    {
        array_map(function ($rule) {
            Rules::assert($rule);
        }, $this->cmd::middleware());

        return $this;
    }

    /**
     *. execute command
     * @return void
     */
    private function execute()
    {
        $this->cmd::handle($this->params);
    }
}
