<?php

namespace JonesRussell\PhpFigGuide\Tests\PSR13;

use JonesRussell\PhpFigGuide\PSR13\HypermediaLink;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for HypermediaLink implementation.
 *
 * @category Hypermedia
 * @package  JonesRussell\PhpFigGuide\Tests\PSR13
 * @author   Russell Jones <jonesrussell42@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/jonesrussell/php-fig-guide
 */
class HypermediaLinkTest extends TestCase
{
    /**
     * Test basic link creation.
     *
     * @return void
     */
    public function testBasicLinkCreation(): void
    {
        $link = new HypermediaLink('/users/123');
        
        $this->assertEquals('/users/123', $link->getHref());
        $this->assertFalse($link->isTemplated());
        $this->assertEmpty($link->getRels());
        $this->assertEmpty($link->getAttributes());
    }

    /**
     * Test templated link detection.
     *
     * @return void
     */
    public function testTemplatedLink(): void
    {
        $link = new HypermediaLink('/users/{id}');
        
        $this->assertTrue($link->isTemplated());
    }

    /**
     * Test adding relationships.
     *
     * @return void
     */
    public function testAddingRelationships(): void
    {
        $link = new HypermediaLink('/users/123');
        $newLink = $link->withRel('self')->withRel('next');
        
        $this->assertNotSame($link, $newLink);
        $this->assertCount(2, $newLink->getRels());
        $this->assertContains('self', $newLink->getRels());
        $this->assertContains('next', $newLink->getRels());
    }

    /**
     * Test removing relationships.
     *
     * @return void
     */
    public function testRemovingRelationships(): void
    {
        $link = new HypermediaLink('/users/123');
        $newLink = $link->withRel('self')->withRel('next')->withoutRel('next');
        
        $this->assertNotSame($link, $newLink);
        $this->assertCount(1, $newLink->getRels());
        $this->assertContains('self', $newLink->getRels());
        $this->assertNotContains('next', $newLink->getRels());
    }

    /**
     * Test adding attributes.
     *
     * @return void
     */
    public function testAddingAttributes(): void
    {
        $link = new HypermediaLink('/users/123');
        $newLink = $link->withAttribute('title', 'User Profile');
        
        $this->assertNotSame($link, $newLink);
        $this->assertCount(1, $newLink->getAttributes());
        $this->assertEquals('User Profile', $newLink->getAttributes()['title']);
    }

    /**
     * Test removing attributes.
     *
     * @return void
     */
    public function testRemovingAttributes(): void
    {
        $link = new HypermediaLink('/users/123');
        $newLink = $link->withAttribute('title', 'User Profile')
                       ->withoutAttribute('title');
        
        $this->assertNotSame($link, $newLink);
        $this->assertEmpty($newLink->getAttributes());
    }

    /**
     * Test changing href.
     *
     * @return void
     */
    public function testChangingHref(): void
    {
        $link = new HypermediaLink('/users/123');
        $newLink = $link->withHref('/users/456');
        
        $this->assertNotSame($link, $newLink);
        $this->assertEquals('/users/456', $newLink->getHref());
    }
} 