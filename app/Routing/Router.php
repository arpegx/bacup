<?php declare(strict_types=1);

namespace Arpegx\Bacup\Routing;

use Arpegx\Bacup\Command\Help;
use Arpegx\Bacup\Command\Init;
use Arpegx\Bacup\Command\Track;
use Webmozart\Assert\Assert;

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

    private array $params = array();

    /**
     *. handle user input
     * @return void
     */
    public function handle(array $argv)
    {
        try {
            $this
                ->resolve($argv)
                ->middleware()
                ->execute();

        } catch (\Exception $e) {
            print $e->getMessage();
            exit(1);
        }
    }

    /**
     *. resolve command
     * @return static
     */
    private function resolve(array $argv)
    {
        switch (true) {
            case sizeof($argv) >= 2:
                $this->cmd = key_exists($argv[1], $this->routes) ? $this->routes[$argv[1]] : Help::class;
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

            extract(call_user_func([Rules::class, $rule]));
            Assert::notFalse($result, $message);

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