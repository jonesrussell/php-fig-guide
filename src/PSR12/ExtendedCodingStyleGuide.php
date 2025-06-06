<?php

declare(strict_types=1);

namespace JonesRussell\PhpFigGuide\PSR12;

/**
 * Extended Coding Style Guide for PSR-12.
 *
 * This class defines constants representing the rules of PSR-12
 * and provides methods to retrieve the style guide and rule descriptions.
 *
 * @category Coding_Style
 * @package  JonesRussell\PhpFigGuide\PSR12
 * @author   Russell Jones <jonesrussell42@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/jonesrussell/php-fig-guide
 */
class ExtendedCodingStyleGuide
{
    // Constants representing PSR-12 rules
    public const LINE_ENDINGS = 'Unix LF';
    public const FILE_ENDING = 'Single blank line';
    public const CLOSING_TAG = 'Omit closing ?> tag';
    public const MAX_LINE_LENGTH = 80;
    public const NAMESPACE_BLANK_LINE = 'One blank line after namespace declaration';
    public const BRACE_POSITION = 'Opening braces must be on the same line as the declaration';

    /**
     * Get the style guide as an associative array.
     *
     * This method returns an array of PSR-12 rules defined as constants.
     *
     * @return array
     */
    public function getStyleGuide(): array
    {
        return [
            'line_endings' => self::LINE_ENDINGS,
            'file_ending' => self::FILE_ENDING,
            'closing_tag' => self::CLOSING_TAG,
            'max_line_length' => self::MAX_LINE_LENGTH,
            'namespace_blank_line' => self::NAMESPACE_BLANK_LINE,
            'brace_position' => self::BRACE_POSITION,
        ];
    }

    /**
     * Get a brief description of each rule.
     *
     * This method returns an array of descriptions for each PSR-12 rule.
     *
     * @return array
     */
    public function getRuleDescriptions(): array
    {
        return [
            'line_endings' => 'Files MUST use Unix LF line endings.',
            'file_ending' => 'Files MUST end with a single blank line.',
            'closing_tag' => 'The closing `?>` tag MUST be omitted from files containing only PHP.',
            'max_line_length' => 'Lines SHOULD be 80 characters or less.',
            'namespace_blank_line' => 'There MUST be one blank line after namespace declarations.',
            'brace_position' => 'Opening braces MUST be on the same line as the statement.',
        ];
    }
}
