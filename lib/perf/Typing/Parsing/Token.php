<?php

namespace perf\Typing\Parsing;

/**
 *
 */
class Token
{

    const T_OPENING_BRACKET = 'OPENING_BRACKET';
    const T_COLON           = 'COLON';
    const T_CLOSING_BRACKET = 'CLOSING_BRACKET';
    const T_SQUARE_BRACKETS = 'SQUARE_BRACKETS';
    const T_PIPE            = 'PIPE';
    const T_LABEL           = 'LABEL';

    /**
     * Token type.
     *
     * @var string
     */
    private $type;

    /**
     * Token content.
     *
     * @var string
     */
    private $content;

    /**
     * Position where the token was extracted from the original type specification string.
     *
     * @var int
     */
    private $offset;

    /**
     * Constructor.
     *
     * @param string $type
     * @param string $content
     * @param int $offset
     * @return void
     */
    public function __construct($type, $content, $offset)
    {
        $this->type    = $type;
        $this->content = $content;
        $this->offset  = $offset;
    }

    /**
     *
     *
     * @return bool
     */
    public function isOpeningBracket()
    {
        return (self::T_OPENING_BRACKET === $this->type);
    }

    /**
     *
     *
     * @return bool
     */
    public function isColon()
    {
        return (self::T_COLON === $this->type);
    }

    /**
     *
     *
     * @return bool
     */
    public function isClosingBracket()
    {
        return (self::T_CLOSING_BRACKET === $this->type);
    }

    /**
     *
     *
     * @return bool
     */
    public function isSquareBrackets()
    {
        return (self::T_SQUARE_BRACKETS === $this->type);
    }

    /**
     *
     *
     * @return bool
     */
    public function isPipe()
    {
        return (self::T_PIPE === $this->type);
    }

    /**
     *
     *
     * @return bool
     */
    public function isLabel()
    {
        return (self::T_LABEL === $this->type);
    }

    /**
     *
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     *
     *
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }
}
