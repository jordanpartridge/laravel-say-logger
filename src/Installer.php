<?php

namespace JordanPartridge\LaravelSayLogger;

use Composer\Script\Event;

class Installer
{
    /**
     * Handle the post-install-cmd Composer event
     */
    public static function install(Event $event): void
    {
        // Check if we're in a Laravel Zero project
        if (!self::isLaravelZero()) {
            return;
        }

        $io = $event->getIO();
        $io->write('🔊 Configuring Say Logger for Laravel Zero...');

        // Auto-configure Laravel Zero
        self::configureLaravelZero();

        $io->write('✅ Say Logger configured successfully!');
        $io->write('🎉 Your log messages will now be spoken aloud!');
        
        // Speak the welcome message
        self::sayWelcome();
    }

    /**
     * Check if we're in a Laravel Zero project
     */
    private static function isLaravelZero(): bool
    {
        $appConfigPath = getcwd() . '/config/app.php';
        $composerJsonPath = getcwd() . '/composer.json';
        
        // Check if it's a Laravel Zero project
        if (file_exists($composerJsonPath)) {
            $composerJson = json_decode(file_get_contents($composerJsonPath), true);
            if (isset($composerJson['require']['laravel-zero/framework'])) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Configure Laravel Zero to use Say Logger
     */
    private static function configureLaravelZero(): void
    {
        $appConfigPath = getcwd() . '/config/app.php';
        
        if (!file_exists($appConfigPath)) {
            return;
        }

        $appConfig = file_get_contents($appConfigPath);
        $serviceProvider = 'JordanPartridge\\\\LaravelSayLogger\\\\LaravelSayLoggerServiceProvider';
        
        // Check if service provider is already registered
        if (strpos($appConfig, $serviceProvider) !== false) {
            return;
        }

        // Add service provider to the providers array
        $pattern = '/\'providers\'\s*=>\s*\[\s*([^\]]+)\s*\]/';
        if (preg_match($pattern, $appConfig, $matches)) {
            $providers = $matches[1];
            $newProviders = $providers . ",\n        '" . $serviceProvider . "'";
            $replacement = "'providers' => [\n        " . $newProviders . "\n    ]";
            $appConfig = preg_replace($pattern, $replacement, $appConfig);
            
            file_put_contents($appConfigPath, $appConfig);
        }
    }

    /**
     * Speak welcome message
     */
    private static function sayWelcome(): void
    {
        try {
            $message = "Say Logger installed successfully! Your log messages will now be spoken aloud.";
            $command = "say -v Alex " . escapeshellarg($message);
            shell_exec($command);
        } catch (\Exception $e) {
            // Silently fail if say command doesn't work
        }
    }
}