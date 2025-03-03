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

    /**
     *. ctor
     * @param array $params
     */
    public function __construct(
        private array $params = ["app" => "bacup", "command" => ""],
    ) {
    }

    /**
     *. handle user input
     * @return void
     */
    public function handle()
    {
        $this->params["command"] ??= "";

        try {
            $this
                ->resolve()
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
    private function resolve()
    {
        if (key_exists($this->params["command"], $this->routes)) {
            $this->cmd = $this->routes[$this->params["command"]];
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