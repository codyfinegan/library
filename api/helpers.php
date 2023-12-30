<?php

/**
 * Global helper functions.
 */


if (!function_exists('isJSONRequest')) {
    function isJSONRequest(): bool
    {
        $contentType = $_SERVER['HTTP_CONTENT_TYPE'] ?? '';
        return str_contains($contentType, 'application/json');
    }
}

if (!function_exists('env')) {
    function env($key, $default = null): mixed
    {
        if (array_key_exists($key, $_ENV)) {
            return $_ENV[$key];
        }

        return $default;
    }
}

if (!function_exists('storage')) {
    function storage(string $dir, ?string $file = null, bool $touch = true): string
    {
        $path = __DIR__ . '/storage/' . $dir;
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        if (!is_dir($path)) {
            throw new Exception('Unable to create storage directory "' . $path . '"');
        }

        if ($file) {
            $path = $path . '/' . $file;
            if ($touch && !touch($path)) {
                throw new \Exception('Unable to create "' . $path . '".');
            }
        }

        return $touch ? realpath($path) : $path;
    }
}

if (!function_exists('e')) {
    function e(): void
    {
        $caller = debug_backtrace(!DEBUG_BACKTRACE_PROVIDE_OBJECT | DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        $i = 0;
        if (!empty($caller[0]['file']) && str_ends_with($caller[0]['file'], 'api/helpers.php')) {
            $i++;
        }

        $line = $caller[$i]['line'] ?? null;
        $file = $caller[$i]['file'] ?? null;

        $args = func_get_args();
        $isJSON = false;

        if ($line && $file) {
            array_unshift($args, compact('file', 'line'));
        }

        // Check if headers are already sent
        if (headers_sent()) {
            // Content type
            $headers = headers_list();
            $contentType = null;
            foreach ($headers as $header) {
                if (str_starts_with(strtolower($header), 'content-type:')) {
                    $contentType = $header;
                    break;
                }
            }

            $isJSON = $contentType && str_contains($contentType, 'application/json');
        }

        if ($isJSON) {
            echo json_encode($args);
            return;
        }

        echo '<pre>';
        foreach ($args as $arg) {
            var_dump($arg);
        }
        echo '</pre>';
    }
}

if (!function_exists('de')) {
    function de(): never
    {
        e(...func_get_args());
        exit;
    }
}