<?php

namespace JonesRussell\PhpFigGuide\Tests\PSR3;

use JonesRussell\PhpFigGuide\PSR3\SmartLogger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

/**
 * Class SmartLoggerTest
 *
 * This class tests the SmartLogger functionality.
 * It verifies that the logger correctly writes messages to a file,
 * handles context, and logs messages at various levels.
 *
 * @category Logging_Test
 * @package  JonesRussell\PhpFigGuide\Tests\PSR3
 * @author   Russell Jones <jonesrussell42@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/jonesrussell/php-fig-guide
 */
class SmartLoggerTest extends TestCase
{
    private string $_logFile;
    private SmartLogger $_logger;

    /**
     * Set up the test environment.
     *
     * This method initializes the SmartLogger instance and prepares
     * a temporary log file for testing.
     *
     * @return void
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
     *
     * This method cleans up the temporary log file after each test.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        if (file_exists($this->_logFile)) {
            unlink($this->_logFile);
        }
    }

    /**
     * Test that logs are written to the file.
     *
     * This test verifies that a log message is correctly written to
     * the log file and that the log file contains the expected content.
     *
     * @return void
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
     *
     * This test checks that the logger correctly interpolates context
     * values into the log message.
     *
     * @return void
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
     *
     * This test verifies that messages logged at various levels are
     * correctly written to the log file.
     *
     * @return void
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
