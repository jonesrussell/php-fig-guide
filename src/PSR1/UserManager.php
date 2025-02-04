<?php

/**
 * Example implementation of PSR-1 Basic Coding Standard.
 * 
 * This class demonstrates proper naming conventions and basic structure
 * according to PSR-1 guidelines.
 */

namespace JonesRussell\PhpFigGuide\PSR1;

/**
 * User management class following PSR-1 standards.
 * 
 * Demonstrates:
 * - StudlyCaps for class name
 * - Constants in UPPER_CASE
 * - Methods in camelCase
 */
class UserManager
{
    /**
     * Version number of the implementation.
     */
    const VERSION = '1.0.0';

    /**
     * Error type constant for not found errors.
     */
    const ERROR_TYPE_NOT_FOUND = 'not_found';

    /**
     * Get user information by ID.
     *
     * @param  int $id The user ID to retrieve
     * @return array User data with 'id' and 'name' keys
     */
    public function getUserById($id)
    {
        // Implementation
        return ['id' => $id, 'name' => 'John Doe'];
    }
}
