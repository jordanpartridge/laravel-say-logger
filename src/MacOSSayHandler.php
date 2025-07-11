<?php

namespace JordanPartridge\LaravelSayLogger;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;
use Monolog\Level;
use Symfony\Component\Process\Process;

class MacOSSayHandler extends AbstractProcessingHandler
{
    public function __construct($level = Level::Debug, bool $bubble = true)
    {
        // Force the handler to accept all levels
        parent::__construct(Level::Debug, $bubble);
    }
    protected function getVoiceForLevel(string $level): string
    {
        $levelName = strtolower($level);
        $voice = config('say-logger.voices.' . $levelName, 'Alex');
        
        // Fallback to Alex if the configured voice doesn't exist
        if (!$this->isVoiceAvailable($voice)) {
            return 'Alex';
        }
        
        return $voice;
    }
    
    protected function isVoiceAvailable(string $voice): bool
    {
        try {
            $process = new Process(['say', '-v', $voice, '']);
            $process->run();
            return $process->isSuccessful();
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function write(LogRecord $record): void
    {
        if (!config('say-logger.enabled', true)) {
            return;
        }

        $voice = $this->getVoiceForLevel($record->level->getName());
        $message = $record->message;
        
        // Clean up the message for speech
        $message = strip_tags($message);
        $message = preg_replace('/\s+/', ' ', $message);
        $message = trim($message);
        
        // Don't speak empty messages
        if (empty($message)) {
            return;
        }

        try {
            $process = new Process(['say', '-v', $voice, $message]);
            $process->run(); // Use run() to wait for completion so they don't overlap
        } catch (\Exception $e) {
            // Silently fail if say command doesn't work
        }
    }
}