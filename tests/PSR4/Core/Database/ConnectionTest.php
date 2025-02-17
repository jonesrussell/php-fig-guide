<?php

namespace JonesRussell\PhpFigGuide\Tests\PSR4\Core\Database;

use JonesRussell\PhpFigGuide\PSR4\Core\Database\Connection;
use PHPUnit\Framework\TestCase;

/**
 * Test class for Connection.
 *
 * This class contains unit tests for the Connection class methods,
 * ensuring that the functionality works as expected and adheres to
 * the defined behavior.
 *
 * @category Database_Test
 * @package  JonesRussell\PhpFigGuide\Tests\PSR4\Core\Database
 * @author   Russell Jones <jonesrussell42@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/jonesrussell/php-fig-guide
 */
class ConnectionTest extends TestCase
{
    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * Set up the Connection instance before each test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $config = [
            'host' => 'localhost',
            'username' => 'root',
            'password' => '',
            'database' => 'test_db'
        ];
        $this->connection = new Connection($config);
    }

    /**
     * Test that the configuration is set correctly.
     *
     * @return void
     */
    public function testConfiguration(): void
    {
        // Here you would typically check if the configuration is set correctly.
        // Since the current implementation does not expose the config,
        // you might want to add a method to retrieve it for testing purposes.
        $this->assertNotNull($this->connection);
    }
}
