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
 *
 * @category Blog
 * @package  JonesRussell\PhpFigGuide\PSR4\Post
 * @author   Russell Jones <jonesrussell42@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/jonesrussell/php-fig-guide
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
