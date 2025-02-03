<?php

namespace JonesRussell\PhpFigGuide\PSR11;

interface ContainerInterface
{
    public function get($id);
    public function has($id): bool;
    public function set(string $id, $service): void;
}
