<?php

namespace Library;

use DI\Bridge\Slim\Bridge as AppBuilder;
use Exception;
use Library\Cache\Cache;
use Psr\Http\Server\MiddlewareInterface;
use Slim\App;
use Whoops\Util\Misc;

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

    public function errors(): self
    {
        $whoops = new \Whoops\Run();
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());

        if (Misc::isAjaxRequest() || isJSONRequest()) {
            $jsonHandler = new \Whoops\Handler\JsonResponseHandler();
            $jsonHandler->setJsonApi(true);
            $whoops->pushHandler($jsonHandler);
        }
        if (Misc::isCommandLine()) {
            $cliHandler = new \Whoops\Handler\PlainTextHandler();
            $whoops->pushHandler($cliHandler);
        }

        $whoops->register();

        return $this;
    }

    public function env(string $root): self
    {
        if (file_exists($root . '/.env')) {
            $dotenv = \Dotenv\Dotenv::createImmutable($root);
            $dotenv->load();
        }

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

    public function registerRoutes(string $routesPath): self
    {
        // Hacky check if we're running relatively or not
        $base = 0;
        if (!empty($_SERVER['REQUEST_URI']) && str_starts_with($_SERVER['REQUEST_URI'], '/api/')) {
            $this->app->setBasePath('/api');
            $base = 1;
        }

        $file = storage('cache', "routes_$base.php", false);
        $this->app->getRouteCollector()->setCacheFile($file);

        /** @var Cache $cache */
        $cache = $this->app->getContainer()->get('cache');

        $routeFiles = $cache->get('routes');
        $writeCache = false;
        if (!$routeFiles) {
            // Scan the $routes directory for all routing groups
            $routeFiles = scandir($routesPath, SCANDIR_SORT_DESCENDING);
            $writeCache = true;
        }
        $files = [];

        foreach ($routeFiles as $file) {
            if (!preg_match('/^([a-z0-9\-]+)\.php$/', $file, $match)) {
                continue;
            }
            $name = $match[1] === 'default' ? '' : '/' . $match[1];
            $callable = include $routesPath . '/' . $file;

//            if (is_array($callable)) {
//                // @TODO handle patterns passed
//            }

            if (is_callable($callable)) {
                $this->app->group($name, $callable);
                $files[] = $file;
            }
        }

        if ($writeCache) {
            $cache->set('routes', $files);
        }

        $this->app->addRoutingMiddleware();

        return $this;
    }

    public function boot(): never
    {
        $this->app->run();
        exit;
    }

    /**
     * @return App
     */
    public function app(): App
    {
        return $this->app;
    }
}