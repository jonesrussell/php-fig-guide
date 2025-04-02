<?php

namespace JonesRussell\PhpFigGuide\PSR13;

use Psr\Link\LinkProviderInterface;
use Psr\Link\LinkInterface;

/**
 * Example implementation of PSR-13 Link Provider.
 *
 * This class demonstrates a practical implementation of a link provider
 * that manages collections of hypermedia links and provides filtering capabilities.
 *
 * @category Hypermedia
 * @package  JonesRussell\PhpFigGuide\PSR13
 * @author   Russell Jones <jonesrussell42@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/jonesrussell/php-fig-guide
 */
class HypermediaLinkProvider implements LinkProviderInterface
{
    /**
     * Collection of links.
     *
     * @var LinkInterface[]
     */
    private array $links = [];

    /**
     * Add a link to the collection.
     *
     * @param LinkInterface $link The link to add
     * @return $this For method chaining
     */
    public function addLink(LinkInterface $link): self
    {
        $this->links[] = $link;
        return $this;
    }

    /**
     * Get all links in the collection.
     *
     * @return LinkInterface[]
     */
    public function getLinks(): array
    {
        return array_values($this->links);
    }

    /**
     * Get all links with the specified relationship.
     *
     * @param string $rel The relationship to filter by
     * @return LinkInterface[]
     */
    public function getLinksByRel($rel): array
    {
        return array_values(
            array_filter(
                $this->links,
                fn(LinkInterface $link) => in_array($rel, $link->getRels())
            )
        );
    }
}
