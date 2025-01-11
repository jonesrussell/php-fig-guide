<?php

namespace JonesRussell\PhpFigGuide\Tests\PSR3;

use JonesRussell\PhpFigGuide\PSR3\SmartLogger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

class SmartLoggerTest extends TestCase
{
    private string $logFile;
    private SmartLogger $logger;

    protected function setUp(): void
    {
        $this->logFile = sys_get_temp_dir() . '/test.log';
        $this->logger = new SmartLogger($this->logFile, 'https://hooks.slack.com/test');
        
        // Clean up any existing log file
        if (file_exists($this->logFile)) {
            unlink($this->logFile);
        }
    }

    protected function tearDown(): void
    {
        if (file_exists($this->logFile)) {
            unlink($this->logFile);
        }
    }

    public function testLogWritesToFile(): void
    {
        $message = 'Test log message';
        $this->logger->info($message);

        $this->assertFileExists($this->logFile);
        $contents = file_get_contents($this->logFile);
        $this->assertStringContainsString($message, $contents);
        $this->assertStringContainsString('[info]', $contents);
    }

    public function testLogWithContext(): void
    {
        $this->logger->error('User {user} not found', ['user' => 'john']);

        $contents = file_get_contents($this->logFile);
        $this->assertStringContainsString('User john not found', $contents);
        $this->assertStringContainsString('[error]', $contents);
    }

    public function testLogLevels(): void
    {
        $levels = [
            LogLevel::EMERGENCY,
            LogLevel::ALERT,
            LogLevel::CRITICAL,
            LogLevel::ERROR,
            LogLevel::WARNING,
            LogLevel::NOTICE,
            LogLevel::INFO,
            LogLevel::DEBUG
        ];

        foreach ($levels as $level) {
            $this->logger->log($level, "Test $level message");
            $contents = file_get_contents($this->logFile);
            $this->assertStringContainsString("[$level]", $contents);
        }
    }
} 