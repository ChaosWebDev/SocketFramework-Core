<?php
namespace Chaos\Core\Console\Commands;

use Chaos\Core\Console\Command;

class ServerStart extends Command
{
    public function getName(): string
    {
        return 'server:start';
    }

    public function getDescription(): string
    {
        return 'Start a socket server. Usage: php chaos server:start --type=telnet --host=0.0.0.0 --port=8023 [--daemon]';
    }

    /**
     * $args is the raw arg list from Kernel (argv slice after command).
     * Example: ['--type=telnet', '--port=8023']
     */
    public function handle(array $args = []): void
    {
        // parse simple long options from $args
        $opts = $this->parseArgs($args);

        $type = $opts['type'] ?? 'telnet';
        $host = $opts['host'] ?? '0.0.0.0';
        $port = (int) ($opts['port'] ?? ($type === 'telnet' ? 23 : 8000));
        $daemon = isset($opts['daemon']);

        // Build config / environment for the App Server
        $config = [
            'type' => $type,
            'host' => $host,
            'port' => $port,
            'daemon' => $daemon,
            'max_clients' => (int) ($opts['max_clients'] ?? 50),
        ];

        // Use the skeleton app Server wrapper (adjust namespace/path if needed)
        $serverClass = 'App\\Server\\Server';
        if (!class_exists($serverClass)) {
            $this->line("Server class {$serverClass} not found. Make sure skeleton/app/Server/Server.php is autoloaded.");
            return;
        }

        // Optionally: run as daemon on *nix if requested (basic support)
        if ($daemon && PHP_OS_FAMILY !== 'Windows') {
            if (function_exists('pcntl_fork')) {
                $pid = pcntl_fork();
                if ($pid === -1) {
                    $this->line("Failed to fork process for daemon mode.");
                } elseif ($pid > 0) {
                    $this->line("Server started in background. PID: {$pid}");
                    return;
                } else {
                    if (function_exists('posix_setsid') && posix_setsid() === -1) {
                        $this->line("Failed to setsid()");
                    }
                }
            }
        } elseif ($daemon && PHP_OS_FAMILY === 'Windows') {
            $this->line("Daemon mode not supported on Windows. Use `start /b php chaos server:start ...` instead.");
        }

        try {
            /** @var App\Server\Server $server */
            $server = new $serverClass($config);
            $this->line("Starting {$type} server on {$host}:{$port} ...");
            $server->start();
        } catch (\Throwable $e) {
            $this->line("Server failed to start: " . $e->getMessage());
        }
    }

    private function parseArgs(array $args): array
    {
        $out = [];
        foreach ($args as $a) {
            // --key=value or --flag
            if (strpos($a, '--') === 0) {
                $a = substr($a, 2);
                if (strpos($a, '=') !== false) {
                    [$k, $v] = explode('=', $a, 2);
                    $out[$k] = $v;
                } else {
                    $out[$a] = true; // flag
                }
            }
        }
        return $out;
    }

    private function line(string $s): void
    {
        echo $s . PHP_EOL;
    }
}
