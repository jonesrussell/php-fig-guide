<?php

namespace JonesRussell\PhpFigGuide\Tests\PSR1;

use JonesRussell\PhpFigGuide\PSR1\UserManager;
use PHPUnit\Framework\TestCase;

class UserManagerTest extends TestCase
{
    private UserManager $userManager;

    protected function setUp(): void
    {
        $this->userManager = new UserManager();
    }

    public function testGetUserById(): void
    {
        $result = $this->userManager->getUserById(1);
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertEquals(1, $result['id']);
        $this->assertEquals('John Doe', $result['name']);
    }

    public function testConstants(): void
    {
        $this->assertEquals('1.0.0', UserManager::VERSION);
        $this->assertEquals('not_found', UserManager::ERROR_TYPE_NOT_FOUND);
    }
} 