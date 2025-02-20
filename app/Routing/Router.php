<?php declare(strict_types=1);

namespace Arpegx\Bacup\Routing;

use Arpegx\Bacup\Command\Help;

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
        private array $params = ["bacup", null],
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
     * @throws \Exception
     * @return static
     */
    private function middleware()
    {
        foreach ($this->cmd::middleware() as $middleware) {
            $validated = call_user_func([Rules::class, $middleware]);

            $validated["result"] ?: throw new \Exception($validated["message"]);
        }
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