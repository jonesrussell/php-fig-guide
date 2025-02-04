<?php

require_once __DIR__ . '/../../vendor/autoload.php'; // Include the Composer autoloader

/**
 * ================================
 * First let's create a couple of classes
 * that we can use to test the container
 * ================================
 */

/**
 * Mock class for a database connection
 * 
 * @property string $connectionMessage
 * @method   void __construct()
 * @method   string connect()
 * @method   void log(string $message)
 */
class DatabaseConnection
{
    /**
     * Message returned when the database is connected
     * 
     * @var string
     */
    public readonly string $connectionMessage;

    /**
     * Constructor for the DatabaseConnection class
     */
    public function __construct()
    {
        $this->connectionMessage = "Database connected!";
    }

    /**
     * Connect to the database
     * 
     * @return string
     */
    public function connect(): string
    {
        return $this->connectionMessage;
    }
}

/**
 * Mock class for a logger
 * 
 * @method void log(string $message)
 */
class Logger
{
    /**
     * Log a message
     * 
     * @param string $message
     */
    public function logline(string $message): void
    {
        echo "Log: $message\n\n";
    }
}

// Create a container
$container = new \JonesRussell\PhpFigGuide\PSR11\ExampleContainer(); // Use the full namespace

/**
 * Register services
 */
$container->set('database', new DatabaseConnection());  // Register the database connection
$container->set('logger', new Logger());              // Register the logger

/**
 * Retrieve services
 */
$database = $container->get('database');
$logger = $container->get('logger');

/**
 * Log a custom message
 */
$logger->logline("This is a log message."); // Output: Log: This is a log message. 

/**
 * Using the services
 */
$connectionMessage = $database->connect(); // Get the connection message
$logger->logline($connectionMessage); // Log the connection message

/**
 * Print the contents of the container
 */
function printContainer($container): void
{
    $logger = $container->get('logger'); // Retrieve the logger from the container
    $logger->logline("Container Services:"); // Log the header

    // Call the printServices method from the container
    $container->printServices("Current Services in Container:"); // Print the services in the container
}

// Print the contents of the container at the end
printContainer($container);
