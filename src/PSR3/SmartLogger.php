<?php

namespace JonesRussell\PhpFigGuide\PSR3;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

class SmartLogger extends AbstractLogger
{
    private $logFile;
    private $slackWebhook;

    public function __construct(string $logFile, string $slackWebhook)
    {
        $this->logFile = $logFile;
        $this->slackWebhook = $slackWebhook;
    }

    public function log($level, $message, array $context = array())
    {
        // Format the message
        $timestamp = date('Y-m-d H:i:s');
        $message = $this->interpolate($message, $context);
        $logLine = "[$timestamp] [$level] $message" . PHP_EOL;
        
        // Always write to file
        file_put_contents($this->logFile, $logLine, FILE_APPEND);
        
        // Send critical and emergency messages to Slack
        if (in_array($level, [LogLevel::CRITICAL, LogLevel::EMERGENCY])) {
            $this->notifySlack($level, $message);
        }
    }

    private function notifySlack($level, $message)
    {
        $emoji = $level === LogLevel::EMERGENCY ? '🔥' : '⚠️';
        $payload = json_encode([
            'text' => "$emoji *$level*: $message"
        ]);

        // Send to Slack (simplified for example)
        $ch = curl_init($this->slackWebhook);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }

    private function interpolate($message, array $context = array())
    {
        $replace = array();
        foreach ($context as $key => $val) {
            $replace['{' . $key . '}'] = $val;
        }
        return strtr($message, $replace);
    }
}
