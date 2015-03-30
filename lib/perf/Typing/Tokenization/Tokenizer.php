<?php

namespace perf\Typing\Tokenization;

use perf\Typing\Exception\InvalidTypeSpecificationException;

/**
 *
 */
class Tokenizer
{

    /**
     *
     *
     * @param string $typeSpecification
     * @return string[]
     * @throws InvalidTypeSpecificationException
     */
    public function tokenize($typeSpecification)
    {
        if (!is_string($typeSpecification)) {
            throw new InvalidTypeSpecificationException('Invalid type specification provided (expected string).');
        }

        $matches = array();

        preg_match_all(
            '#([a-zA-Z0-9_\\\\]+|{|}|:|\\[\\]|\\|)#',
            $typeSpecification,
            $matches
        );

        $tokenStrings = $matches[1];

        if (count($tokenStrings) < 1) {
            throw new InvalidTypeSpecificationException('Invalid type specification provided.');
        }

        $totalLength = 0;

        foreach ($tokenStrings as $tokenString) {
            $totalLength += strlen($tokenString);
        }

        if ($totalLength !== strlen($typeSpecification)) {
            throw new InvalidTypeSpecificationException();
        }

        static $map = array(
            '{'  => Token::TYPE_OPENING_BRACKET,
            ':'  => Token::TYPE_COLON,
            '}'  => Token::TYPE_CLOSING_BRACKET,
            '[]' => Token::TYPE_SQUARE_BRACKETS,
            '|'  => Token::TYPE_PIPE,
        );

        $tokens = array();

        foreach ($tokenStrings as $tokenString) {
            if (array_key_exists($tokenString, $map)) {
                $type = $map[$tokenString];
            } else {
                $type = Token::TYPE_LABEL;
            }

            $token = new Token($type, $tokenString);

            $tokens[] = $token;
        }
        
        return $tokens;
    }
}
