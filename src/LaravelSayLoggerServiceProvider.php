<?php

namespace JordanPartridge\LaravelSayLogger;

use Illuminate\Support\ServiceProvider;
use Illuminate\Log\LogManager;
use Monolog\Logger;

class LaravelSayLoggerServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Auto-detect Laravel Zero and register LogServiceProvider if needed
        if ($this->isLaravelZero() && !$this->app->bound('log')) {
            $this->app->register(\Illuminate\Log\LogServiceProvider::class);
        }
        
        $this->mergeConfigFrom(__DIR__.'/../config/say-logger.php', 'say-logger');
    }
    
    /**
     * Check if we're running in Laravel Zero
     */
    private function isLaravelZero(): bool
    {
        return class_exists('LaravelZero\Framework\Application') || 
               class_exists('LaravelZero\Framework\Kernel');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/say-logger.php' => config_path('say-logger.php'),
        ], 'config');

        // Auto-configure Laravel Zero logging if needed
        if ($this->isLaravelZero()) {
            $this->configureLaravelZeroLogging();
        }

        // Register custom log driver
        $logManager = $this->app->make('log');
        
        // Check if the log manager has the extend method
        if (method_exists($logManager, 'extend')) {
            $logManager->extend('say', function ($app, $config) {
                $logger = new Logger('say');
                $level = $config['level'] ?? 'debug';
                $monologLevel = Logger::toMonologLevel($level);
                $logger->pushHandler(new MacOSSayHandler($monologLevel));
                return $logger;
            });
        } else {
            // Laravel Zero might be using NullLogger, so we need to replace it
            $this->app->singleton('log', function ($app) {
                return new \Illuminate\Log\LogManager($app);
            });
            
            // Now register our custom driver
            $this->app->make('log')->extend('say', function ($app, $config) {
                $logger = new Logger('say');
                $level = $config['level'] ?? 'debug';
                $monologLevel = Logger::toMonologLevel($level);
                $logger->pushHandler(new MacOSSayHandler($monologLevel));
                return $logger;
            });
        }
    }
    
    /**
     * Configure Laravel Zero logging to use say logger by default
     */
    private function configureLaravelZeroLogging(): void
    {
        // Add say channel to logging configuration if not already present
        $loggingConfig = config('logging', []);
        
        if (!isset($loggingConfig['channels']['say'])) {
            config([
                'logging.channels.say' => [
                    'driver' => 'say',
                    'level' => env('LOG_LEVEL', 'debug'),
                ]
            ]);
        }
        
        // Set say as default channel if no custom channel is configured
        if (!env('LOG_CHANNEL') && config('logging.default') !== 'say') {
            config(['logging.default' => 'say']);
        }
    }
}