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
    /**
     * Database configuration array.
     *
     * @var array
     */
    private array $config;

    /**
     * Constructor
     *
     * Initializes the database connection with the provided configuration.
     *
     * @param array $config Database configuration array
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    // Additional methods for database operations can be added here
}
