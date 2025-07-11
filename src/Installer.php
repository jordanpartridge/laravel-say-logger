<?php

namespace JordanPartridge\LaravelSayLogger;

// use Composer\Script\Event;

class Installer
{
    /**
     * Handle the post-install-cmd Composer event
     */
    public static function install($event = null): void
    {
        // Check if we're in a Laravel Zero project
        if (!self::isLaravelZero()) {
            return;
        }

        if ($event && method_exists($event, 'getIO')) {
            $io = $event->getIO();
            $io->write('🔊 Configuring Say Logger for Laravel Zero...');
        } else {
            echo "🔊 Configuring Say Logger for Laravel Zero...\n";
        }

        // Auto-configure Laravel Zero
        self::configureLaravelZero();

        if ($event && method_exists($event, 'getIO')) {
            $io = $event->getIO();
            $io->write('✅ Say Logger configured successfully!');
            $io->write('🎉 Your log messages will now be spoken aloud!');
        } else {
            echo "✅ Say Logger configured successfully!\n";
            echo "🎉 Your log messages will now be spoken aloud!\n";
        }
        
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
            // A special surprise welcome message with multiple voices
            $voices = ['Alex', 'Victoria', 'Fred', 'Kathy'];
            $messages = [
                'Welcome to the future of debugging!',
                'Your logs will now sing to you!',
                'Let the symphony of code begin!',
                'Say Logger is ready to rock and roll!'
            ];
            
            // Play the messages in sequence with different voices
            foreach ($messages as $index => $message) {
                $voice = $voices[$index % count($voices)];
                $command = "say -v {$voice} -r 200 " . escapeshellarg($message);
                shell_exec($command);
                usleep(500000); // Small pause between messages
            }
            
            // Grand finale with a special effect
            $finaleMessage = "🎉 May your bugs be few and your logs be loud! 🎉";
            $command = "say -v Cellos -r 150 " . escapeshellarg($finaleMessage);
            shell_exec($command);
            
        } catch (\Exception $e) {
            // Silently fail if say command doesn't work
        }
    }
}