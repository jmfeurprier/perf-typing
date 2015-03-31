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
     * @return Token[]
     * @throws InvalidTypeSpecificationException
     */
    public function tokenize($typeSpecification)
    {
        if (!is_string($typeSpecification)) {
            $type = gettype($typeSpecification);

            throw new InvalidTypeSpecificationException(
                "Invalid type specification provided: expected string, got {$type}."
            );
        }

        $rawTokens = $this->splitTypeSpecification($typeSpecification);

        $this->validateRawTokens($typeSpecification, $rawTokens);

        return $this->buildTokens($rawTokens);
    }

    /**
     *
     *
     * @param string $typeSpecification
     * @return string[]
     * @throws InvalidTypeSpecificationException
     */
    private function splitTypeSpecification($typeSpecification)
    {
        // @todo Improve regex for classes/internal types.
        $regex   = '#([a-zA-Z0-9_\\\\]+|{|}|:|\\[\\]|\\|)#';
        $matches = array();
        $flags   = (\PREG_PATTERN_ORDER | \PREG_OFFSET_CAPTURE);

        preg_match_all($regex, $typeSpecification, $matches, $flags);

        $rawTokens = $matches[1];

        return $rawTokens;
    }

    /**
     *
     *
     * @param string $typeSpecification
     * @param string[] $rawTokens
     * @return void
     * @throws InvalidTypeSpecificationException
     */
    private function validateRawTokens($typeSpecification, array $rawTokens)
    {
        if (count($rawTokens) < 1) {
            throw new InvalidTypeSpecificationException(
                'Invalid type specification provided: no type specification found.'
            );
        }

        $totalLength = 0;

        foreach ($rawTokens as $rawToken) {
            $totalLength += strlen($rawToken[0]);
        }

        if ($totalLength !== strlen($typeSpecification)) {
            throw new InvalidTypeSpecificationException(
                'Invalid type specification provided: unexpected characters found.'
            );
        }
    }

    /**
     *
     *
     * @param string[] $rawTokens
     * @return Token[]
     * @throws InvalidTypeSpecificationException
     */
    private function buildTokens(array $rawTokens)
    {
        static $map = array(
            '{'  => Token::T_OPENING_BRACKET,
            ':'  => Token::T_COLON,
            '}'  => Token::T_CLOSING_BRACKET,
            '[]' => Token::T_SQUARE_BRACKETS,
            '|'  => Token::T_PIPE,
        );

        $tokens = array();

        foreach ($rawTokens as $rawToken) {
            $tokenString = $rawToken[0];
            $tokenOffset = $rawToken[1];

            if (array_key_exists($tokenString, $map)) {
                $type = $map[$tokenString];
            } else {
                $type = Token::T_LABEL;
            }

            $token = new Token($type, $tokenString, $tokenOffset);

            $tokens[] = $token;
        }
        
        return $tokens;
    }
}
