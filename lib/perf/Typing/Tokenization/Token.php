<?php

namespace perf\Typing\Tokenization;

/**
 *
 */
class Token
{

    const TYPE_OPENING_BRACKET = 'OPENING_BRACKET';
    const TYPE_COLON           = 'COLON';
    const TYPE_CLOSING_BRACKET = 'CLOSING_BRACKET';
    const TYPE_SQUARE_BRACKETS = 'SQUARE_BRACKETS';
    const TYPE_PIPE            = 'PIPE';
    const TYPE_LABEL           = 'LABEL';

    /**
     *
     *
     * @param string $type
     * @param string $content
     * @return void
     */
    public function __construct($type, $content)
    {
        $this->type    = $type;
        $this->content = $content;
    }

    /**
     *
     *
     * @return bool
     */
    public function isOpeningBracket()
    {
        return (self::TYPE_OPENING_BRACKET === $this->type);
    }

    /**
     *
     *
     * @return bool
     */
    public function isColon()
    {
        return (self::TYPE_COLON === $this->type);
    }

    /**
     *
     *
     * @return bool
     */
    public function isClosingBracket()
    {
        return (self::TYPE_CLOSING_BRACKET === $this->type);
    }

    /**
     *
     *
     * @return bool
     */
    public function isSquareBrackets()
    {
        return (self::TYPE_SQUARE_BRACKETS === $this->type);
    }

    /**
     *
     *
     * @return bool
     */
    public function isPipe()
    {
        return (self::TYPE_PIPE === $this->type);
    }

    /**
     *
     *
     * @return bool
     */
    public function isLabel()
    {
        return (self::TYPE_LABEL === $this->type);
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
}
