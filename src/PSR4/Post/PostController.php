<?php

/**
 * Example implementation of PSR-4 Autoloading Standard.
 * 
 * This file demonstrates proper namespace and class structure
 * according to PSR-4 guidelines.
 */

namespace JonesRussell\PhpFigGuide\PSR4\Post;

/**
 * Blog post controller demonstrating PSR-4 namespace structure.
 * 
 * The namespace JonesRussell\PhpFigGuide\PSR4\Post matches
 * the directory structure src/PSR4/Post/.
 */
class PostController
{
    /**
     * Get the index response.
     *
     * @return array Response data
     */
    public function index(): array
    {
        return ['status' => 'Ready to blog!'];
    }
}
