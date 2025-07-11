<?php

namespace JordanPartridge\LaravelSayLogger;

use Illuminate\Support\ServiceProvider;
use Illuminate\Log\LogManager;
use Monolog\Logger;

class LaravelSayLoggerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/say-logger.php', 'say-logger');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/say-logger.php' => config_path('say-logger.php'),
        ], 'config');

        // Register custom log driver
        $this->app->make('log')->extend('say', function ($app, $config) {
            $logger = new Logger('say');
            $level = $config['level'] ?? 'debug';
            $monologLevel = Logger::toMonologLevel($level);
            $logger->pushHandler(new MacOSSayHandler($monologLevel));
            return $logger;
        });
    }
}