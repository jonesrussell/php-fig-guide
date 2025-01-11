<?php

/**
 * Example implementation of PSR-3 Logger Interface.
 * 
 * This class demonstrates a logger that writes to files and sends critical
 * messages to Slack, following PSR-3 guidelines.
 */

namespace JonesRussell\PhpFigGuide\PSR3;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

/**
 * Smart logger implementation that writes to files and Slack.
 * 
 * This logger:
 * - Writes all messages to a log file
 * - Sends critical and emergency messages to Slack
 * - Supports message context interpolation
 */
class SmartLogger extends AbstractLogger
{
    /**
     * Path to the log file.
     *
     * @var string
     */
    private $_logFile;

    /**
     * Slack webhook URL for notifications.
     *
     * @var string
     */
    private $_slackWebhook;

    /**
     * Initialize the logger with file and Slack configuration.
     *
     * @param string $logFile Path to the log file
     * @param string $slackWebhook Slack webhook URL
     */
    public function __construct(string $logFile, string $slackWebhook)
    {
        $this->_logFile = $logFile;
        $this->_slackWebhook = $slackWebhook;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed                $level   Log level
     * @param string|\Stringable   $message Message to log
     * @param array               $context Context data for interpolation
     * @return void
     */
    public function log($level, string|\Stringable $message, array $context = []): void
    {
        // Format the message
        $timestamp = date('Y-m-d H:i:s');
        $message = $this->_interpolate((string)$message, $context);
        $logLine = "[$timestamp] [$level] $message" . PHP_EOL;
        
        // Always write to file
        file_put_contents($this->_logFile, $logLine, FILE_APPEND);
        
        // Send critical and emergency messages to Slack
        if (in_array($level, [LogLevel::CRITICAL, LogLevel::EMERGENCY])) {
            $this->_notifySlack($level, $message);
        }
    }

    /**
     * Send a notification to Slack.
     *
     * @param string $level   Log level
     * @param string $message Message to send
     */
    private function _notifySlack($level, $message)
    {
        $emoji = $level === LogLevel::EMERGENCY ? '🔥' : '⚠️';
        $payload = json_encode([
            'text' => "$emoji *$level*: $message"
        ]);

        // Send to Slack (simplified for example)
        $ch = curl_init($this->_slackWebhook);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }

    /**
     * Interpolates context values into message placeholders.
     *
     * @param string $message Message with placeholders
     * @param array  $context Values to replace placeholders
     * @return string Interpolated message
     */
    private function _interpolate($message, array $context = array())
    {
        $replace = array();
        foreach ($context as $key => $val) {
            $replace['{' . $key . '}'] = $val;
        }
        return strtr($message, $replace);
    }
}
