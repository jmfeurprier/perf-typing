<?php

namespace Jmf\TypeValidation\Parsing;

use Jmf\TypeValidation\Exception\InvalidTypeSpecificationException;

readonly class Tokenizer
{
    private const TYPE_MAP = [
        '{'  => Token::T_OPENING_BRACKET,
        ':'  => Token::T_COLON,
        '}'  => Token::T_CLOSING_BRACKET,
        '[]' => Token::T_SQUARE_BRACKETS,
        '|'  => Token::T_PIPE,
    ];

    /**
     * @return Token[]
     *
     * @throws InvalidTypeSpecificationException
     */
    public function tokenize(string $typeSpecification): array
    {
        $rawTokens = $this->getRawTokens($typeSpecification);

        $this->validateRawTokens($typeSpecification, $rawTokens);

        return $this->buildTokens($rawTokens);
    }

    /**
     * @return array<array{0: string, 1: int}>
     *
     * @throws InvalidTypeSpecificationException
     */
    private function getRawTokens(string $typeSpecification): array
    {
        // @todo Improve regex for classes/internal types.
        $regex   = '#([a-zA-Z0-9_\\\\]+|{|}|:|\\[\\]|\\|)#';
        $matches = [];
        $flags   = (\PREG_PATTERN_ORDER | \PREG_OFFSET_CAPTURE);

        preg_match_all($regex, $typeSpecification, $matches, $flags);

        return $matches[1];
    }

    /**
     * @param array<array{0: string, 1: int}> $rawTokens
     *
     * @throws InvalidTypeSpecificationException
     */
    private function validateRawTokens(
        string $typeSpecification,
        array $rawTokens
    ): void {
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
     * @param array<array{0: string, 1: int}> $rawTokens
     *
     * @return Token[]
     *
     * @throws InvalidTypeSpecificationException
     */
    private function buildTokens(array $rawTokens): array
    {
        $tokens = [];

        foreach ($rawTokens as $rawToken) {
            $tokenString = $rawToken[0];
            $tokenOffset = $rawToken[1];
            $tokenType   = $this->getTokenType($tokenString);

            $tokens[] = new Token($tokenType, $tokenString, $tokenOffset);
        }

        return $tokens;
    }

    private function getTokenType(string $tokenString): string
    {
        return self::TYPE_MAP[$tokenString] ?? Token::T_LABEL;
    }
}
