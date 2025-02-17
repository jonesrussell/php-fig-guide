<?php

declare(strict_types=1);

namespace JonesRussell\PhpFigGuide\Tests\PSR12;

use PHPUnit\Framework\TestCase;
use JonesRussell\PhpFigGuide\PSR12\ExtendedCodingStyleGuide;

/**
 * Test class for ExtendedCodingStyleGuide.
 *
 * This class contains unit tests for the ExtendedCodingStyleGuide methods,
 * ensuring that the functionality works as expected and adheres to
 * the defined behavior.
 *
 * @category Coding_Style_Test
 * @package  JonesRussell\PhpFigGuide\Tests\PSR12
 * @author   Russell Jones <jonesrussell42@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/jonesrussell/php-fig-guide
 */
final class ExtendedCodingStyleGuideTest extends TestCase
{
    /**
     * Test the getStyleGuide method.
     *
     * This test verifies that the getStyleGuide method returns
     * an array of PSR-12 rules defined as constants.
     *
     * @return void
     */
    public function testGetStyleGuide(): void
    {
        $guide = new ExtendedCodingStyleGuide();
        $this->assertIsArray($guide->getStyleGuide());
        $this->assertArrayHasKey('line_endings', $guide->getStyleGuide());
        $this->assertArrayHasKey('file_ending', $guide->getStyleGuide());
        $this->assertArrayHasKey('closing_tag', $guide->getStyleGuide());
        $this->assertArrayHasKey('max_line_length', $guide->getStyleGuide());
        $this->assertArrayHasKey('namespace_blank_line', $guide->getStyleGuide());
        $this->assertArrayHasKey('brace_position', $guide->getStyleGuide());
    }

    /**
     * Test the getRuleDescriptions method.
     *
     * This test verifies that the getRuleDescriptions method returns
     * an array of descriptions for each PSR-12 rule.
     *
     * @return void
     */
    public function testGetRuleDescriptions(): void
    {
        $guide = new ExtendedCodingStyleGuide();
        $descriptions = $guide->getRuleDescriptions();

        $this->assertIsArray($descriptions);
        $this->assertArrayHasKey('line_endings', $descriptions);
        $this->assertArrayHasKey('file_ending', $descriptions);
        $this->assertArrayHasKey('closing_tag', $descriptions);
        $this->assertArrayHasKey('max_line_length', $descriptions);
        $this->assertArrayHasKey('namespace_blank_line', $descriptions);
        $this->assertArrayHasKey('brace_position', $descriptions);
    }
}
