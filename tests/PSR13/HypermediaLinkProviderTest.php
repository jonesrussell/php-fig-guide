<?php

namespace JonesRussell\PhpFigGuide\Tests\PSR13;

use JonesRussell\PhpFigGuide\PSR13\HypermediaLink;
use JonesRussell\PhpFigGuide\PSR13\HypermediaLinkProvider;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for HypermediaLinkProvider implementation.
 *
 * @category Hypermedia
 * @package  JonesRussell\PhpFigGuide\Tests\PSR13
 * @author   Russell Jones <jonesrussell42@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/jonesrussell/php-fig-guide
 */
class HypermediaLinkProviderTest extends TestCase
{
    /**
     * Test adding links to provider.
     *
     * @return void
     */
    public function testAddingLinks(): void
    {
        $provider = new HypermediaLinkProvider();
        $link1 = new HypermediaLink('/users/123');
        $link2 = new HypermediaLink('/users/456');
        
        $provider->addLink($link1)->addLink($link2);
        
        $this->assertCount(2, $provider->getLinks());
        $this->assertContains($link1, $provider->getLinks());
        $this->assertContains($link2, $provider->getLinks());
    }

    /**
     * Test getting links by relationship.
     *
     * @return void
     */
    public function testGettingLinksByRel(): void
    {
        $provider = new HypermediaLinkProvider();
        
        // Create links with different relationships
        $selfLink = (new HypermediaLink('/users/123'))->withRel('self');
        $nextLink = (new HypermediaLink('/users/456'))->withRel('next');
        $prevLink = (new HypermediaLink('/users/789'))->withRel('prev');
        
        $provider->addLink($selfLink)
                ->addLink($nextLink)
                ->addLink($prevLink);
        
        // Test getting links by relationship
        $selfLinks = $provider->getLinksByRel('self');
        $this->assertCount(1, $selfLinks);
        $this->assertContains($selfLink, $selfLinks);
        
        $nextLinks = $provider->getLinksByRel('next');
        $this->assertCount(1, $nextLinks);
        $this->assertContains($nextLink, $nextLinks);
    }

    /**
     * Test getting links with multiple relationships.
     *
     * @return void
     */
    public function testLinksWithMultipleRels(): void
    {
        $provider = new HypermediaLinkProvider();
        
        // Create a link with multiple relationships
        $link = (new HypermediaLink('/users/123'))
            ->withRel('self')
            ->withRel('next');
        
        $provider->addLink($link);
        
        // Test getting links by each relationship
        $selfLinks = $provider->getLinksByRel('self');
        $this->assertCount(1, $selfLinks);
        $this->assertContains($link, $selfLinks);
        
        $nextLinks = $provider->getLinksByRel('next');
        $this->assertCount(1, $nextLinks);
        $this->assertContains($link, $nextLinks);
    }

    /**
     * Test getting links with non-existent relationship.
     *
     * @return void
     */
    public function testGettingNonExistentRel(): void
    {
        $provider = new HypermediaLinkProvider();
        $link = new HypermediaLink('/users/123');
        $provider->addLink($link);
        
        $links = $provider->getLinksByRel('non-existent');
        $this->assertEmpty($links);
    }
} 