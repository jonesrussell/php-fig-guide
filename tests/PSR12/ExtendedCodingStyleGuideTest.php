<?php

declare(strict_types=1);

namespace JonesRussell\PhpFigGuide\PSR12;

use PHPUnit\Framework\TestCase;

final class ExtendedCodingStyleGuideTest extends TestCase
{
    public function testGetStyleGuide(): void
    {
        $guide = new ExtendedCodingStyleGuide();
        $this->assertIsArray($guide->getStyleGuide());
        $this->assertArrayHasKey('line_endings', $guide->getStyleGuide());
        $this->assertArrayHasKey('file_ending', $guide->getStyleGuide());
        $this->assertArrayHasKey('closing_tag', $guide->getStyleGuide());
        $this->assertArrayHasKey('max_line_length', $guide->getStyleGuide());
    }
} 