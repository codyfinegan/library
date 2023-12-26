<?php

namespace Library;

use DI\Bridge\Slim\Bridge as AppBuilder;
use Exception;
use Psr\Http\Server\MiddlewareInterface;
use Slim\App;

/**
 * Kernel class that bootstraps the base application, configuring the
 * DI container and the slim framework.
 */
class Kernel
{
    protected App $app;

    protected function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * @param string $container_path
     * @return self
     * @throws Exception
     */
    public static function make(string $container_path): self
    {
        // Set the container
        $builder = new \DI\ContainerBuilder();
        $builder->addDefinitions($container_path);
        $container = $builder->build();

        $app = AppBuilder::create($container);
        $container->set('app', $app);
        $container->set(App::class, $app);

        $kernel = new static($app);
        $container->set(Kernel::class, $kernel);

        return $kernel;
    }

    public function configure(): self
    {
        $this->app->addRoutingMiddleware();
        $this->app->addErrorMiddleware(true, true, true);
        return $this;
    }

    /**
     * @param array|MiddlewareInterface|callable|string $middleware
     * @return $this
     */
    public function middleware(array|MiddlewareInterface|callable|string $middleware): self
    {
        if (!is_array($middleware)) {
            $middleware = [$middleware];
        }

        foreach ($middleware as $m) {
            $this->app->add($m);
        }

        return $this;
    }

    public function registerRoutes(string $routes_path): self
    {
        $callable = include $routes_path;

        if (is_callable($callable)) {
            $callable($this->app);
        }

        return $this;
    }

    /**
     * @return App
     */
    public function app(): App
    {
        return $this->app;
    }
}