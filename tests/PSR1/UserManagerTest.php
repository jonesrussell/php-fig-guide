<?php

namespace JonesRussell\PhpFigGuide\Tests\PSR1;

use JonesRussell\PhpFigGuide\PSR1\UserManager;
use PHPUnit\Framework\TestCase;

/**
 * Test class for UserManager.
 *
 * This class contains unit tests for the UserManager class methods,
 * ensuring that the functionality works as expected and adheres to
 * the defined behavior.
 *
 * @category User_Management_Test
 * @package  JonesRussell\PhpFigGuide\Tests\PSR1
 * @author   Russell Jones <jonesrussell42@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/jonesrussell/php-fig-guide
 */
class UserManagerTest extends TestCase
{
    /**
     * @var UserManager
     */
    private UserManager $userManager;

    /**
     * Set up the UserManager instance before each test.
     *
     * This method is called before each test method is executed,
     * ensuring that a fresh instance of UserManager is available
     * for testing.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->userManager = new UserManager();
    }

    /**
     * Test retrieving user by ID.
     *
     * This test verifies that the getUserById method returns an
     * array containing the correct user ID and name for a given
     * user ID.
     *
     * @return void
     */
    public function testGetUserById(): void
    {
        $result = $this->userManager->getUserById(1);
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertEquals(1, $result['id']);
        $this->assertEquals('John Doe', $result['name']);
    }

    /**
     * Test constants in UserManager.
     *
     * This test checks that the constants defined in the UserManager
     * class return the expected values.
     *
     * @return void
     */
    public function testConstants(): void
    {
        $this->assertEquals('1.0.0', UserManager::VERSION);
        $this->assertEquals('not_found', UserManager::ERROR_TYPE_NOT_FOUND);
    }
} 
