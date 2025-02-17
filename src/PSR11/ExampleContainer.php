<?php

namespace JonesRussell\PhpFigGuide\PSR11;

use JonesRussell\PhpFigGuide\PSR11\ContainerInterface;
use JonesRussell\PhpFigGuide\PSR11\NotFoundExceptionInterface;

/**
 * Example implementation of the ContainerInterface.
 *
 * This class provides a simple container for managing services.
 *
 * @category Container
 * @package  JonesRussell\PhpFigGuide\PSR11
 * @author   Russell Jones <jonesrussell42@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/jonesrussell/php-fig-guide
 */
class ExampleContainer implements ContainerInterface
{
    /**
     * Services stored in the container.
     *
     * @var array<string, mixed>
     */
    private array $services = [];

    /**
     * ExampleContainer constructor.
     * Prints a message indicating that the container has been created.
     */
    public function __construct()
    {
        echo "Container created!\n\n"; // Print message when the container is created

        $this->printServices("Initial Services in Container:"); // Print the services in the container
    }

    /**
     * Print the services stored in the container.
     *
     * @param string $header Optional header for the output.
     * @return void
     */
    public function printServices(string $header = "Services in Container:"): void
    {
        echo $header . "\n" . json_encode($this->services, JSON_PRETTY_PRINT) . "\n";
    }

    /**
     * Set an entry in the container.
     *
     * @param string $id      The identifier of the entry to set.
     * @param mixed  $service The service to be stored in the container.
     * @return void
     */
    public function set(string $id, $service): void
    {
        $this->services[$id] = $service;
    }

    /**
     * Get an entry from the container.
     *
     * @param  string $id The identifier of the entry to get.
     * @return mixed
     * @throws NotFoundExceptionInterface
     */
    public function get($id)
    {
        /**
         * Throws an exception if the service is not found.
         *
         * Example:
         * ```php
         * try {
         *     $service = $container->get('non.existent.service');
         * } catch (NotFoundExceptionInterface $e) {
         *     echo "Service not found!";
         * }
         *
         * @throws NotFoundExceptionInterface
         */
        if (!$this->has($id)) {
            throw new class extends \Exception implements NotFoundExceptionInterface {
            };
        }

        /**
         * Returns the service from the container.
         *
         * Example:
         * ```php
         * $service = $container->get('database');
         * echo $service->connect(); // Output: Database connected!
         * ```
         *
         * @throws ContainerExceptionInterface
         */
        return $this->services[$id];
    }

    /**
     * Check if a service exists in the container.
     *
     * @param  string $id The identifier of the entry to check.
     * @return bool
     */
    public function has($id): bool
    {
        return isset($this->services[$id]);
    }

    /**
     * Get all services stored in the container.
     *
     * @return array<string, mixed>
     */
    public function getServices(): array
    {
        return $this->services;
    }
}
