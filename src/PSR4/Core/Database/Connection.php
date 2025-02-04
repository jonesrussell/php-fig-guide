<?php

namespace JonesRussell\PhpFigGuide\PSR4\Core\Database;

/**
 * Database connection class
 *
 * This class provides a connection to a database using a configuration array.
 * It supports basic database operations and error handling.
 *
 * @category Database
 * @package  JonesRussell\PhpFigGuide\PSR4\Core\Database
 * @author   Russell Jones <jonesrussell42@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/jonesrussell/php-fig-guide
 */
class Connection
{
    private array $config;

    /**
     * Constructor
     *
     * @param array $config Database configuration array
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }
}
