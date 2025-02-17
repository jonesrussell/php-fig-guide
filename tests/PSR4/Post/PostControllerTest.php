<?php

namespace JonesRussell\PhpFigGuide\Tests\PSR4\Post;

use JonesRussell\PhpFigGuide\PSR4\Post\PostController;
use PHPUnit\Framework\TestCase;

/**
 * Test class for PostController.
 *
 * This class contains unit tests for the PostController methods,
 * ensuring that the functionality works as expected and adheres to
 * the defined behavior.
 *
 * @category Blog_Test
 * @package  JonesRussell\PhpFigGuide\Tests\PSR4\Post
 * @author   Russell Jones <jonesrussell42@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/jonesrussell/php-fig-guide
 */
class PostControllerTest extends TestCase
{
    /**
     * @var PostController
     */
    private PostController $controller;

    /**
     * Set up the PostController instance before each test.
     *
     * This method is called before each test method is executed,
     * ensuring that a fresh instance of PostController is available
     * for testing.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->controller = new PostController();
    }

    /**
     * Test the index method.
     *
     * This test verifies that the index method returns an array
     * containing the expected status message.
     *
     * @return void
     */
    public function testIndex(): void
    {
        $result = $this->controller->index();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('status', $result);
        $this->assertEquals('Ready to blog!', $result['status']);
    }
}
