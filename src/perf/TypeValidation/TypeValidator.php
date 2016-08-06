<?php

namespace perf\TypeValidation;

use perf\Caching\CacheClient;
use perf\TypeValidation\Parsing\Parser;

/**
 *
 *
 */
class TypeValidator
{

    /**
     *
     *
     * @var Parser
     */
    private $parser;

    /**
     *
     *
     * @var CacheClient
     */
    private $cacheClient;

    /**
     *
     *
     *
     * @return TypeValidatorBuilder
     */
    public static function createDefault()
    {
        return static::createBuilder()->build();
    }

    /**
     *
     *
     *
     * @return TypeValidatorBuilder
     */
    public static function createBuilder()
    {
        return new TypeValidatorBuilder();
    }

    /**
     *
     *
     * @param Parser $parser
     * @param CacheClient $cacheClient
     * @return void
     */
    public function __construct(Parser $parser, CacheClient $cacheClient)
    {
        $this->parser      = $parser;
        $this->cacheClient = $cacheClient;
    }

    /**
     *
     *
     * @param string $typeSpecification
     * @param mixed $value
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function isValid($typeSpecification, $value)
    {
        if (!is_string($typeSpecification)) {
            throw new InvalidTypeSpecificationException(
                "Invalid type specification provided (expected string)."
            );
        }

        $typeTree = $this->getTypeTree($typeSpecification);

        return $typeTree->isValid($value);
    }

    /**
     *
     *
     * @param string $typeSpecification
     * @return TypeTree
     * @throws \InvalidArgumentException
     */
    private function getTypeTree($typeSpecification)
    {
        $cacheEntryId = __CLASS__ . "|typeSpecification:{$typeSpecification}";

        $typeTree = $this->cacheClient->tryFetch($cacheEntryId);

        if (!$typeTree) {
            $typeTree = $this->parser->parse($typeSpecification);

            $this->cacheClient->store($cacheEntryId, $typeTree);
        }

        return $typeTree;
    }
}
