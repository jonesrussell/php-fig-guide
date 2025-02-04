<?php

namespace JonesRussell\PhpFigGuide\Tests\PSR3;

use JonesRussell\PhpFigGuide\PSR3\SmartLogger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

/**
 * Class SmartLoggerTest
 * 
 * This class tests the SmartLogger functionality.
 */
class SmartLoggerTest extends TestCase
{
    private $_logFile;
    private $_logger;

    /**
     * Set up the test environment.
     */
    protected function setUp(): void
    {
        $this->_logFile = sys_get_temp_dir() . '/test.log';
        $this->_logger = new SmartLogger($this->_logFile, 'https://hooks.slack.com/test');
        
        // Clean up any existing log file
        if (file_exists($this->_logFile)) {
            unlink($this->_logFile);
        }
    }

    /**
     * Tear down the test environment.
     */
    protected function tearDown(): void
    {
        if (file_exists($this->_logFile)) {
            unlink($this->_logFile);
        }
    }

    /**
     * Test that logs are written to the file.
     */
    public function testLogWritesToFile(): void
    {
        $message = 'Test log message';
        $this->_logger->info($message);

        $this->assertFileExists($this->_logFile);
        $contents = file_get_contents($this->_logFile);
        $this->assertStringContainsString($message, $contents);
        $this->assertStringContainsString('[info]', $contents);
    }

    /**
     * Test logging with context.
     */
    public function testLogWithContext(): void
    {
        $this->_logger->error('User {user} not found', ['user' => 'john']);

        $contents = file_get_contents($this->_logFile);
        $this->assertStringContainsString('User john not found', $contents);
        $this->assertStringContainsString('[error]', $contents);
    }

    /**
     * Test different log levels.
     */
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
            $this->_logger->log($level, "Test $level message");
            $contents = file_get_contents($this->_logFile);
            $this->assertStringContainsString("[$level]", $contents);
        }
    }
} 
