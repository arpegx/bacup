<?php declare(strict_types=1);

namespace Arpegx\Bacup\Routing;

use Arpegx\Bacup\Command\Help;

class Router
{
    private string $cmd = Help::class;
    private string $namespace = 'Arpegx\Bacup\Command\\';

    public function __construct(
        private array $params = ["bacup", null],
    ) {
    }
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

    private function resolve()
    {
        if (class_exists($class = $this->namespace . ucfirst($this->params[1]))) {
            $this->cmd = $class;
        }
        return $this;
    }
    private function middleware()
    {
        foreach ($this->cmd::middleware() as $middleware) {
            $validated = call_user_func([Rules::class, $middleware]);
            $validated["result"] ?: throw new \Exception($validated["message"]);
        }
        return $this;
    }
    private function execute()
    {
        $this->cmd::handle($this->params);
    }
}