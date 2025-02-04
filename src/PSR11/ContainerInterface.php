<?php

namespace JonesRussell\PhpFigGuide\PSR11;

use JonesRussell\PhpFigGuide\PSR11\NotFoundExceptionInterface;
use JonesRussell\PhpFigGuide\PSR11\ContainerExceptionInterface;

interface ContainerInterface
{
    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * Example:
     * ```php
     * $container = new ExampleContainer();
     * $container->set('database', new DatabaseConnection());
     * $database = $container->get('database');
     * ```
     *
     * @param  string $id
     * @return mixed
     * @throws NotFoundExceptionInterface If the entry is not found.
     * @throws ContainerExceptionInterface If there is an error retrieving the entry.
     */
    public function get($id);

    /**
     * Returns true if the container can return an entry for the given identifier.
     *
     * Example:
     * ```php
     * $container = new ExampleContainer();
     * $container->set('database', new DatabaseConnection());
     * $exists = $container->has('database'); // Returns true
     * ```
     *
     * @param  string $id
     * @return bool
     */
    public function has($id): bool;

    /**
     * Set an entry in the container.
     *
     * Example:
     * ```php
     * $container = new ExampleContainer();
     * $container->set('database', new DatabaseConnection());
     * ```
     *
     * @param string $id
     * @param mixed  $service The service to be stored in the container.
     */
    public function set(string $id, $service): void;
}
