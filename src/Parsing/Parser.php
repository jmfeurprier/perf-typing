<?php

namespace Jmf\TypeValidation\Parsing;

use Jmf\TypeValidation\Exception\InvalidTypeSpecificationException;
use Jmf\TypeValidation\Tree\CollectionTypeNode;
use Jmf\TypeValidation\Tree\LeafTypeNode;
use Jmf\TypeValidation\Tree\MapTypeNode;
use Jmf\TypeValidation\Tree\MultipleTypeNode;
use Jmf\TypeValidation\Tree\TypeNode;
use RuntimeException;

class Parser
{
    private const VALID_ARRAY_KEY_TYPES = [
        'mixed',
        'string',
        'int',
        'integer',
    ];

    /**
     * @var Token[]
     */
    private array $tokens = [];

    public function __construct(
        private readonly Tokenizer $tokenizer
    ) {
    }

    /**
     * @throws InvalidTypeSpecificationException
     */
    public function parse(string $typeSpecification): TypeNode
    {
        $this->tokens = $this->tokenizer->tokenize($typeSpecification);

        $nodes = [];

        while ($this->hasNextToken()) {
            $token = $this->getNextToken();

            if ($token->isLabel()) {
                $nodes[] = $this->parseLabel();
            } elseif ($token->isOpeningBracket()) {
                $nodes[] = $this->parseIndexedArray();
            } else {
                throw new InvalidTypeSpecificationException(
                    "Unexpected token '{$token->getContent()}' at offset {$token->getOffset()}."
                );
            }

            if (!$this->hasNextToken()) { // @phpstan-ignore-line
                break;
            }

            $nextToken = $this->getNextToken();

            if (!$nextToken->isPipe()) {
                throw new InvalidTypeSpecificationException(
                    "Unexpected token '{$nextToken->getContent()}' at offset {$nextToken->getOffset()}."
                );
            }

            $this->popNextToken();

            // Trailing pipe (|) encountered?
            if (!$this->hasNextToken()) { // @phpstan-ignore-line
                throw new InvalidTypeSpecificationException(
                    "Premature end of type specification encountered."
                );
            }
        }

        return $this->mergeTypeNodes($nodes);
    }

    /**
     * @throws InvalidTypeSpecificationException
     */
    private function parseIndexedArray(): TypeNode
    {
        $this->popNextToken();

        $keyTypeNode = $this->parseArrayKeyType();
        $this->parseColon();
        $valueTypeNode = $this->parseArrayValueType();

        $node = new MapTypeNode($keyTypeNode, $valueTypeNode);

        return $this->parseSquareBrackets($node);
    }

    /**
     * @throws InvalidTypeSpecificationException
     */
    private function parseArrayKeyType(): TypeNode
    {
        $token = $this->popNextToken();

        if (!$token->isLabel()) {
            throw new InvalidTypeSpecificationException(
                "Unexpected token '{$token->getContent()}' at offset {$token->getOffset()}."
            );
        }

        $type = $token->getContent();

        if (!in_array($type, self::VALID_ARRAY_KEY_TYPES, true)) {
            throw new InvalidTypeSpecificationException(
                "Unexpected type '{$type}' for array key at offset {$token->getOffset()}."
            );
        }

        return new LeafTypeNode($type);
    }

    /**
     * @throws InvalidTypeSpecificationException
     */
    private function parseColon(): void
    {
        if (!$this->hasNextToken()) {
            throw new InvalidTypeSpecificationException(
                'Premature end of type specification encountered.'
            );
        }

        $token = $this->popNextToken();

        if (!$token->isColon()) {
            throw new InvalidTypeSpecificationException(
                "Unexpected token '{$token->getContent()}' at offset {$token->getOffset()} (expected colon)."
            );
        }
    }

    /**
     * @throws InvalidTypeSpecificationException
     */
    private function parseArrayValueType(): TypeNode
    {
        $nodes = [];

        while ($this->hasNextToken()) {
            $token = $this->getNextToken();

            if ($token->isLabel()) {
                $nodes[] = $this->parseLabel();
            } elseif ($token->isOpeningBracket()) {
                $nodes[] = $this->parseIndexedArray();
            } else {
                throw new InvalidTypeSpecificationException(
                    "Unexpected token '{$token->getContent()}' at offset {$token->getOffset()} (expected colon)."
                );
            }

            if (!$this->hasNextToken()) { // @phpstan-ignore-line
                throw new InvalidTypeSpecificationException(
                    "Premature end of type specification encountered."
                );
            }

            $nextToken = $this->getNextToken();

            if ($nextToken->isPipe()) {
                $this->popNextToken();

                continue;
            }

            if ($nextToken->isClosingBracket()) {
                $this->popNextToken();

                break;
            }

            throw new InvalidTypeSpecificationException(
                "Unexpected token '{$nextToken->getContent()}' at offset {$nextToken->getOffset()}."
            );
        }

        return $this->mergeTypeNodes($nodes);
    }

    /**
     * @throws InvalidTypeSpecificationException
     */
    private function parseLabel(): TypeNode
    {
        $token = $this->popNextToken();

        $node = new LeafTypeNode($token->getContent());

        return $this->parseSquareBrackets($node);
    }

    private function parseSquareBrackets(TypeNode $node): TypeNode
    {
        while ($this->hasNextToken()) {
            if (!$this->getNextToken()->isSquareBrackets()) {
                break;
            }

            $this->popNextToken();

            $node = new CollectionTypeNode($node);
        }

        return $node;
    }

    /**
     * @param TypeNode[] $nodes
     *
     * @throws InvalidTypeSpecificationException
     */
    private function mergeTypeNodes(array $nodes): TypeNode
    {
        if (count($nodes) > 1) {
            return new MultipleTypeNode($nodes);
        }

        if (1 === count($nodes)) {
            return reset($nodes);
        }

        throw new InvalidTypeSpecificationException(
            'Failed to extract tokens from specification type.'
        );
    }

    private function getNextToken(): Token
    {
        if (empty($this->tokens)) {
            throw new RuntimeException();
        }

        return reset($this->tokens);
    }

    private function popNextToken(): Token
    {
        if (empty($this->tokens)) {
            throw new RuntimeException();
        }

        return array_shift($this->tokens);
    }

    private function hasNextToken(): bool
    {
        return !empty($this->tokens);
    }
}
