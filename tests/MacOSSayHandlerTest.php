<?php

namespace JordanPartridge\LaravelSayLogger\Tests;

use PHPUnit\Framework\TestCase;
use JordanPartridge\LaravelSayLogger\MacOSSayHandler;
use Monolog\LogRecord;
use Monolog\Level;

class MacOSSayHandlerTest extends TestCase
{
    public function test_handler_can_be_instantiated()
    {
        $handler = new MacOSSayHandler();
        $this->assertInstanceOf(MacOSSayHandler::class, $handler);
    }

    public function test_handler_processes_log_record()
    {
        $handler = new MacOSSayHandler();
        
        $record = new LogRecord(
            datetime: new \DateTimeImmutable(),
            channel: 'test',
            level: Level::Error,
            message: 'Test error message',
            context: [],
            extra: []
        );

        // This test just ensures no exceptions are thrown
        $this->assertTrue($handler->isHandling($record));
    }
}