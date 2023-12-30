<?php

namespace Library;

use DI\Container;

class Config
{
    protected array $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function build(Container $container): static
    {
        $configPath = $container->get('pathConfig');
        $files = scandir($configPath, SCANDIR_SORT_ASCENDING);
        $data = [];
        foreach ($files as $file) {
            if (!preg_match('/^([a-z0-9\-]+)\.php$/', $file, $matches)) {
                continue;
            }

            $record = include $configPath . '/' . $matches[1] . '.php';
            $data[$matches[1]] = $record;
        }

        return new static($data);
    }

    public function __get(string $name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        throw new \Exception('Unknown config options "' . $name . '"');
    }

    /**
     * @param string $name
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $name, mixed $default = null): mixed {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name] ?? $default;
        }

        // Now if we have a dot, break it into a tree
        $path = [$name];
        if (str_contains($name, '.')) {
            $path = explode('.', $name);
        }

        $data = $this->data;
        foreach ($path as $step) {
            // Next step down in the tree
            $data = $data[$step] ?? $default;

            if (!is_array($data)) {
                break;
            }
        }

        return $data ?? $default;
    }
}