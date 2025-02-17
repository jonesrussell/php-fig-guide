<?php

declare(strict_types=1);

// phpcs:disable PSR1.Files.SideEffects.FoundWithSymbols

namespace JonesRussell\PhpFigGuide\PSR11;

require_once __DIR__ . '/../../vendor/autoload.php'; // Include the Composer autoloader

// Include the ExampleContainer class
use JonesRussell\PhpFigGuide\PSR11\ExampleContainer;

/**
 * Example usage of the container.
 *
 * This script demonstrates how to use the ExampleContainer
 * to register and retrieve services.
 *
 * @category Example
 * @package  JonesRussell\PhpFigGuide\PSR11
 * @author   Russell Jones <jonesrussell42@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/jonesrussell/php-fig-guide
 */

/**
 * ================================
 * First let's create a couple of classes
 * that we can use to test the container
 * ================================
 */

// Example usage
$container = new ExampleContainer();

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
 * Using the services
 */
$connectionMessage = $database->connect(); // Get the connection message
$logger->logline($connectionMessage); // Log the connection message

$logger->logline("This is a log message."); // Output: Log: This is a log message.

/**
 * Print the contents of the container
 *
 * @param ContainerInterface $container The container to print.
 * @return void
 */
function printContainer(ContainerInterface $container): void
{
    $logger = $container->get('logger'); // Retrieve the logger from the container
    $logger->logline("Container Services:"); // Log the header

    // Call the printServices method from the container
    $container->printServices("Current Services in Container:"); // Print the services in the container
}

// Print the contents of the container at the end
printContainer($container);
