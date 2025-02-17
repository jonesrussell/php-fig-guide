<?php

namespace JonesRussell\PhpFigGuide\PSR3;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

/**
 * Example implementation of PSR-3 Logger Interface.
 *
 * This class demonstrates a logger that writes to files and sends critical
 * messages to Slack, following PSR-3 guidelines.
 *
 * @category Logging
 * @package  JonesRussell\PhpFigGuide\PSR3
 * @author   Russell Jones <jonesrussell42@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/jonesrussell/php-fig-guide
 */
class SmartLogger extends AbstractLogger
{
    /**
     * Path to the log file.
     *
     * @var string
     */
    private string $logFile;

    /**
     * Slack webhook URL for notifications.
     *
     * @var string
     */
    private string $slackWebhook;

    /**
     * Initialize the logger with file and Slack configuration.
     *
     * @param string $logFile      Path to the log file
     * @param string $slackWebhook Slack webhook URL
     */
    public function __construct(string $logFile, string $slackWebhook)
    {
        $this->logFile = $logFile;
        $this->slackWebhook = $slackWebhook;
    }

    /**
     * Logs with an arbitrary level.
     *
     * This method formats the log message and writes it to the log file.
     * It also sends critical and emergency messages to Slack.
     *
     * @param  mixed              $level   Log level
     * @param  string|\Stringable $message Message to log
     * @param  array              $context Context data for interpolation
     * @return void
     */
    public function log($level, string|\Stringable $message, array $context = []): void
    {
        // Format the message
        $timestamp = date('Y-m-d H:i:s');
        $message = $this->interpolate((string)$message, $context);
        $logLine = "[$timestamp] [$level] $message" . PHP_EOL;

        // Always write to file
        file_put_contents($this->logFile, $logLine, FILE_APPEND);

        // Send critical and emergency messages to Slack
        if (in_array($level, [LogLevel::CRITICAL, LogLevel::EMERGENCY])) {
            $this->notifySlack($level, $message);
        }
    }

    /**
     * Send a notification to Slack.
     *
     * This method sends a formatted message to the specified Slack webhook
     * for critical and emergency log levels.
     *
     * @param string $level   Log level
     * @param string $message Message to send
     *
     * @return void
     */
    private function notifySlack($level, $message)
    {
        $emoji = $level === LogLevel::EMERGENCY ? '🔥' : '⚠️';
        $payload = json_encode(
            [
                'text' => "$emoji *$level*: $message"
            ]
        );

        // Send to Slack (simplified for example)
        $ch = curl_init($this->slackWebhook);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }

    /**
     * Interpolates context values into message placeholders.
     *
     * This method replaces placeholders in the message with actual values
     * from the context array.
     *
     * @param  string $message Message with placeholders
     * @param  array  $context Values to replace placeholders
     * @return string Interpolated message
     */
    private function interpolate($message, array $context = array())
    {
        $replace = array();
        foreach ($context as $key => $val) {
            $replace['{' . $key . '}'] = $val;
        }
        $interpolatedMessage = strtr($message, $replace);
        return $interpolatedMessage;
    }
}
