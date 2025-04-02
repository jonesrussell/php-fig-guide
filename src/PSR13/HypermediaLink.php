<?php

namespace JonesRussell\PhpFigGuide\PSR13;

use Psr\Link\EvolvableLinkInterface;

/**
 * Example implementation of PSR-13 Hypermedia Links.
 *
 * This class demonstrates a practical implementation of hypermedia links
 * that can be used in REST APIs and HATEOAS applications.
 *
 * @category Hypermedia
 * @package  JonesRussell\PhpFigGuide\PSR13
 * @author   Russell Jones <jonesrussell42@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/jonesrussell/php-fig-guide
 */
class HypermediaLink implements EvolvableLinkInterface
{
    /**
     * The URI of the link.
     *
     * @var string
     */
    private string $href;

    /**
     * Map of relationship names.
     *
     * @var array
     */
    private array $rels = [];

    /**
     * Map of attribute name => value pairs.
     *
     * @var array
     */
    private array $attributes = [];

    /**
     * Whether the link contains template variables.
     *
     * @var bool
     */
    private bool $templated = false;

    /**
     * Create a new hypermedia link.
     *
     * @param string $href The URI of the link
     */
    public function __construct(string $href)
    {
        $this->href = $href;
        $this->templated = strpos($href, '{') !== false;
    }

    /**
     * Get the URI of the link.
     *
     * @return string
     */
    public function getHref(): string
    {
        return $this->href;
    }

    /**
     * Check if the link is templated.
     *
     * @return bool
     */
    public function isTemplated(): bool
    {
        return $this->templated;
    }

    /**
     * Get all relationships of the link.
     *
     * @return array
     */
    public function getRels(): array
    {
        return array_keys($this->rels);
    }

    /**
     * Get all attributes of the link.
     *
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Create a new instance with the specified href.
     *
     * @param string|\Stringable $href The new URI
     * @return static
     */
    public function withHref(string|\Stringable $href): static
    {
        $new = clone $this;
        $new->href = (string)$href;
        $new->templated = strpos($new->href, '{') !== false;
        return $new;
    }

    /**
     * Create a new instance with an additional relationship.
     *
     * @param string $rel The relationship to add
     * @return static
     */
    public function withRel(string $rel): static
    {
        $new = clone $this;
        $new->rels[$rel] = true;
        return $new;
    }

    /**
     * Create a new instance without the specified relationship.
     *
     * @param string $rel The relationship to remove
     * @return static
     */
    public function withoutRel(string $rel): static
    {
        $new = clone $this;
        unset($new->rels[$rel]);
        return $new;
    }

    /**
     * Create a new instance with an additional attribute.
     *
     * @param string $attribute The attribute name
     * @param mixed  $value    The attribute value
     * @return static
     */
    public function withAttribute(string $attribute, mixed $value): static
    {
        $new = clone $this;
        $new->attributes[$attribute] = $value;
        return $new;
    }

    /**
     * Create a new instance without the specified attribute.
     *
     * @param string $attribute The attribute to remove
     * @return static
     */
    public function withoutAttribute(string $attribute): static
    {
        $new = clone $this;
        unset($new->attributes[$attribute]);
        return $new;
    }
} 