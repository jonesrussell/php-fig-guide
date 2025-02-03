<?php

namespace JonesRussell\PhpFigGuide\PSR11;

use JonesRussell\PhpFigGuide\PSR11\NotFoundExceptionInterface;
use JonesRussell\PhpFigGuide\PSR11\ContainerExceptionInterface;

interface ContainerInterface
{
    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id
     * @return mixed
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function get($id);

    /**
     * Returns true if the container can return an entry for the given identifier.
     *
     * @param string $id
     * @return bool
     */
    public function has($id): bool;

    /**
     * Set an entry in the container.
     *
     * @param string $id
     * @param mixed $service
     */
    public function set(string $id, $service): void;
}
