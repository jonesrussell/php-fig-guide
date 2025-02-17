<?php

declare(strict_types=1);

namespace JonesRussell\PhpFigGuide\PSR12;

class ExtendedCodingStyleGuide
{
    public const LINE_ENDINGS = 'Unix LF';
    public const FILE_ENDING = 'Single blank line';
    public const CLOSING_TAG = 'Omit closing ?> tag';
    public const MAX_LINE_LENGTH = 80;

    public function getStyleGuide(): array
    {
        return [
            'line_endings' => self::LINE_ENDINGS,
            'file_ending' => self::FILE_ENDING,
            'closing_tag' => self::CLOSING_TAG,
            'max_line_length' => self::MAX_LINE_LENGTH,
        ];
    }
} 