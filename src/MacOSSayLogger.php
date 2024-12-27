
<?php

namespace YourName\LaravelSayLogger;

use Illuminate\Log\Logger;
use Symfony\Component\Process\Process;

class MacOSSayLogger extends Logger
{
    protected function getVoiceForLevel($level)
    {
        return config('say-logger.voices.' . $level, 'Alex');
    }

    public function log($level, $message, array $context = [])
    {
        parent::log($level, $message, $context);

        if (config('say-logger.enabled')) {
            $voice = $this->getVoiceForLevel($level);
            $process = new Process(['say', '-v', $voice, $message]);
            $process->start();
        }
    }
}
