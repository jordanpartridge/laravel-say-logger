<?php

namespace JordanPartridge\LaravelSayLogger;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;
use Symfony\Component\Process\Process;

class MacOSSayLogger extends AbstractProcessingHandler
{
    protected function getVoiceForLevel($level)
    {
        $levelName = strtolower($level);
        return config('say-logger.voices.' . $levelName, 'Alex');
    }

    protected function write(LogRecord $record): void
    {
        if (config('say-logger.enabled', true)) {
            $voice = $this->getVoiceForLevel($record->level->getName());
            $message = $record->message;
            
            // Clean up the message for speech
            $message = strip_tags($message);
            $message = preg_replace('/\s+/', ' ', $message);
            
            $process = new Process(['say', '-v', $voice, $message]);
            $process->start();
        }
    }
}