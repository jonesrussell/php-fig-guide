<?php

require_once __DIR__ . '/ExampleContainer.php'; // Ensure the ExampleContainer is included

// Mock classes for demonstration
class DatabaseConnection {
    public function connect() {
        return "Database connected!";
    }
}

class Logger {
    public function log($message) {
        echo "Log: $message\n";
    }
}

// Example usage
$container = new \JonesRussell\PhpFigGuide\PSR11\ExampleContainer(); // Use the full namespace

// Registering services
$container->set('database', new DatabaseConnection());
$container->set('logger', new Logger());

// Retrieving services
$database = $container->get('database');
$logger = $container->get('logger');

// Using the services
echo $database->connect(); // Output: Database connected!
$logger->log("This is a log message."); // Output: Log: This is a log message. 
