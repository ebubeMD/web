<?php

namespace Core\Framework;

use Core\Database\DatabaseManager;
use Core\Cache\CacheManager;
use Core\Auth\AuthManager;
use Exception;
use Dotenv\Dotenv;

class Application
{
    private static ?Application $instance = null;
    private Router $router;
    private DatabaseManager $database;
    private CacheManager $cache;
    private AuthManager $auth;
    private array $config = [];
    private bool $debug = false;

    private function __construct()
    {
        $this->loadEnvironment();
        $this->loadConfig();
        $this->initializeServices();
        $this->setupErrorHandling();
    }

    public static function getInstance(): Application
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function run(): void
    {
        try {
            $this->router->dispatch();
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    public function getRouter(): Router
    {
        return $this->router;
    }

    public function getDatabase(): DatabaseManager
    {
        return $this->database;
    }

    public function getCache(): CacheManager
    {
        return $this->cache;
    }

    public function getAuth(): AuthManager
    {
        return $this->auth;
    }

    public function getConfig(string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->config;
        }

        $keys = explode('.', $key);
        $value = $this->config;

        foreach ($keys as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }

    private function loadEnvironment(): void
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
        $dotenv->load();

        $this->debug = $_ENV['APP_DEBUG'] ?? false;
    }

    private function loadConfig(): void
    {
        $configPath = dirname(__DIR__, 2) . '/config';
        
        $configFiles = [
            'app.php',
            'database.php',
            'cache.php',
            'auth.php',
            'mail.php'
        ];

        foreach ($configFiles as $file) {
            $path = $configPath . '/' . $file;
            if (file_exists($path)) {
                $key = basename($file, '.php');
                $this->config[$key] = require $path;
            }
        }
    }

    private function initializeServices(): void
    {
        $this->router = new Router();
        $this->database = new DatabaseManager($this->config['database']);
        $this->cache = new CacheManager($this->config['cache']);
        $this->auth = new AuthManager($this->config['auth']);
    }

    private function setupErrorHandling(): void
    {
        if ($this->debug) {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        } else {
            error_reporting(0);
            ini_set('display_errors', 0);
        }

        set_exception_handler([$this, 'handleException']);
        set_error_handler([$this, 'handleError']);
    }

    public function handleException(Exception $exception): void
    {
        $response = [
            'error' => true,
            'message' => $this->debug ? $exception->getMessage() : 'Internal Server Error',
            'code' => $exception->getCode() ?: 500
        ];

        if ($this->debug) {
            $response['trace'] = $exception->getTraceAsString();
            $response['file'] = $exception->getFile();
            $response['line'] = $exception->getLine();
        }

        http_response_code($response['code']);
        header('Content-Type: application/json');
        echo json_encode($response, JSON_PRETTY_PRINT);
        exit;
    }

    public function handleError($severity, $message, $file, $line): void
    {
        if (!(error_reporting() & $severity)) {
            return;
        }

        throw new \ErrorException($message, 0, $severity, $file, $line);
    }
}