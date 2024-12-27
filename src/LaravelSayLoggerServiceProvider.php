
<?php

namespace YourName\LaravelSayLogger;

use Illuminate\Support\ServiceProvider;

class LaravelSayLoggerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/say-logger.php', 'say-logger');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/say-logger.php' => config_path('say-logger.php'),
        ], 'config');
    }
}
