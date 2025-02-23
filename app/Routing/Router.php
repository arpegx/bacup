<?php declare(strict_types=1);

namespace Arpegx\Bacup\Routing;

use Arpegx\Bacup\Command\Help;
use Webmozart\Assert\Assert;

class Router
{
    /**
     *. command to be executed
     * @var string
     */
    private string $cmd = Help::class;

    /**
     *. fullqualified namespace for cmd
     * @var string
     */
    private string $namespace = 'Arpegx\Bacup\Command\\';

    /**
     *. ctor
     * @param array $params
     */
    public function __construct(
        private array $params = ["bacup", ""],
    ) {
    }

    /**
     *. handle user input
     * @return void
     */
    public function handle()
    {
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
        if (class_exists($class = $this->namespace . ucfirst($this->params[1]))) {
            $this->cmd = $class;
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