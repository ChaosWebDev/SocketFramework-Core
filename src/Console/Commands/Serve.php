<?php

namespace Chaos\Core\Console\Commands;

use Chaos\Core\Console\Command;

class Serve extends Command
{
    public function getName(): string
    {
        return 'serve';
    }

    public function getDescription(): string
    {
        return 'Starts the PHP Development server. This is not the socket server. Defaults to http://127.0.0.1:8000.';
    }

    public function handle(array $args = []): void
    {
        $root = getcwd();

        // If user provided a manual URI: php chaos serve 127.0.0.1:9999
        if (!empty($args[0])) {
            $uri = (strpos($args[0], 'http') === 0) ? $args[0] : "http://{$args[0]}";
        } else {
            // Otherwise, pull from env
            $uri = env('APP_URL', 'http://127.0.0.1:8000');
        }

        $parts = parse_url($uri);
        $host = $parts['host'] ?? '127.0.0.1';
        $port = $parts['port'] ?? 8000;

        $publicPath = $root . DIRECTORY_SEPARATOR . 'public';

        if (!is_dir($publicPath)) {
            echo "Public directory not found: {$publicPath}\n";
            return;
        }

        echo "Starting development server at http://{$host}:{$port}\n";
        echo "Document root: {$publicPath}\n";
        echo "Press Ctrl+C to stop the server\n\n";

        passthru("php -S {$host}:{$port} -t {$publicPath}");
    }
}
