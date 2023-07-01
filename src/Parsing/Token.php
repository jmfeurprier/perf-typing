<?php

namespace Jmf\TypeValidation\Parsing;

readonly class Token
{
    final public const T_OPENING_BRACKET = 'OPENING_BRACKET';

    final public const T_COLON = 'COLON';

    final public const T_CLOSING_BRACKET = 'CLOSING_BRACKET';

    final public const T_SQUARE_BRACKETS = 'SQUARE_BRACKETS';

    final public const T_PIPE = 'PIPE';

    final public const T_LABEL = 'LABEL';

    /**
     * $offset is the position where the token was extracted from the original type specification string.
     */
    public function __construct(
        private string $type,
        private string $content,
        private int $offset
    ) {
    }

    public function isOpeningBracket(): bool
    {
        return (self::T_OPENING_BRACKET === $this->type);
    }

    public function isColon(): bool
    {
        return (self::T_COLON === $this->type);
    }

    public function isClosingBracket(): bool
    {
        return (self::T_CLOSING_BRACKET === $this->type);
    }

    public function isSquareBrackets(): bool
    {
        return (self::T_SQUARE_BRACKETS === $this->type);
    }

    public function isPipe(): bool
    {
        return (self::T_PIPE === $this->type);
    }

    public function isLabel(): bool
    {
        return (self::T_LABEL === $this->type);
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }
}
