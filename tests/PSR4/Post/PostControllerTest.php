<?php

namespace JonesRussell\PhpFigGuide\Tests\PSR4\Post;

use JonesRussell\PhpFigGuide\PSR4\Post\PostController;
use PHPUnit\Framework\TestCase;

class PostControllerTest extends TestCase
{
    private PostController $controller;

    protected function setUp(): void
    {
        $this->controller = new PostController();
    }

    public function testIndex(): void
    {
        $result = $this->controller->index();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('status', $result);
        $this->assertEquals('Ready to blog!', $result['status']);
    }
} 
