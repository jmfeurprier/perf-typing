<?php

namespace perf\TypeValidation;

use perf\Caching\CacheClient;
use perf\TypeValidation\Exception\InvalidTypeSpecificationException;
use perf\TypeValidation\Parsing\Parser;
use perf\TypeValidation\Tree\TypeNode;

class TypeValidator
{
    private Parser $parser;

    private CacheClient $cacheClient;

    public static function createDefault(): self
    {
        return static::createBuilder()->build();
    }

    public static function createBuilder(): TypeValidatorBuilder
    {
        return new TypeValidatorBuilder();
    }

    public function __construct(Parser $parser, CacheClient $cacheClient)
    {
        $this->parser      = $parser;
        $this->cacheClient = $cacheClient;
    }

    /**
     * @param string $typeSpecification
     * @param mixed  $value
     *
     * @return bool
     *
     * @throws InvalidTypeSpecificationException
     */
    public function isValid(string $typeSpecification, $value): bool
    {
        return $this->getTypeTree($typeSpecification)->isValid($value);
    }

    /**
     * @param string $typeSpecification
     *
     * @return TypeNode
     *
     * @throws InvalidTypeSpecificationException
     */
    private function getTypeTree(string $typeSpecification): TypeNode
    {
        $cacheEntryId = $this->getCacheEntryId($typeSpecification);

        $typeTree = $this->cacheClient->tryFetch($cacheEntryId);

        if (!$typeTree) {
            $typeTree = $this->parser->parse($typeSpecification);

            $this->cacheClient->store($cacheEntryId, $typeTree);
        }

        return $typeTree;
    }

    private function getCacheEntryId(string $typeSpecification): string
    {
        return __CLASS__ . "|typeSpecification:{$typeSpecification}";
    }
}
