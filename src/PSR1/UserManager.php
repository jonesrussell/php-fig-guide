<?php

namespace JonesRussell\PhpFigGuide\PSR1;

class UserManager
{
    const VERSION = '1.0.0';
    const ERROR_TYPE_NOT_FOUND = 'not_found';

    public function getUserById($id)
    {
        // Implementation
        return ['id' => $id, 'name' => 'John Doe'];
    }
}
