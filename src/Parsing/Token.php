<?php

namespace perf\TypeValidation\Parsing;

class Token
{
    public const T_OPENING_BRACKET = 'OPENING_BRACKET';
    public const T_COLON           = 'COLON';
    public const T_CLOSING_BRACKET = 'CLOSING_BRACKET';
    public const T_SQUARE_BRACKETS = 'SQUARE_BRACKETS';
    public const T_PIPE            = 'PIPE';
    public const T_LABEL           = 'LABEL';

    private string $type;

    private string $content;

    /**
     * Position where the token was extracted from the original type specification string.
     *
     * @var int
     */
    private int $offset;

    public function __construct(string $type, string $content, int $offset)
    {
        $this->type    = $type;
        $this->content = $content;
        $this->offset  = $offset;
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
