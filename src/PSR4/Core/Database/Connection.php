<?php

namespace JonesRussell\PhpFigGuide\PSR4\Core\Database;

class Connection
{
    private $config;
    
    public function __construct(array $config)
    {
        $this->config = $config;
    }
}
