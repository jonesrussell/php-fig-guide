<?php

namespace JonesRussell\PhpFigGuide\PSR11;

/**
 * Mock class for a logger.
 *
 * This class simulates logging functionality.
 *
 * @category Logging
 * @package  JonesRussell\PhpFigGuide\PSR11
 * @author   Russell Jones <jonesrussell42@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/jonesrussell/php-fig-guide
 */
class Logger
{
    /**
     * Log a message.
     *
     * @param string $message The message to log.
     *
     * @return void
     */
    public function logline(string $message): void
    {
        echo "Log: $message\n\n";
    }
}
