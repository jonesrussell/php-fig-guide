<?php

namespace JonesRussell\PhpFigGuide\PSR11;

/**
 * Mock class for a database connection.
 *
 * This class simulates a database connection.
 *
 * @category Database
 * @package  JonesRussell\PhpFigGuide\PSR11
 * @author   Russell Jones <jonesrussell42@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/jonesrussell/php-fig-guide
 */
class DatabaseConnection
{
    /**
     * Message returned when the database is connected.
     *
     * @var string
     */
    public readonly string $connectionMessage;

    /**
     * Constructor for the DatabaseConnection class.
     */
    public function __construct()
    {
        $this->connectionMessage = "Database connected!";
    }

    /**
     * Connect to the database.
     *
     * @return string
     */
    public function connect(): string
    {
        return $this->connectionMessage;
    }
} 
