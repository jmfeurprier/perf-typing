<?php

namespace Jmf\TypeValidation;

use Jmf\TypeValidation\Exception\InvalidTypeSpecificationException;
use Jmf\TypeValidation\Parsing\Parser;
use Jmf\TypeValidation\Tree\TypeNode;
use perf\Caching\CacheClient;
use perf\Caching\Exception\CachingException;

readonly class TypeValidator
{
    public static function createDefault(): self
    {
        return static::createBuilder()->build();
    }

    public static function createBuilder(): TypeValidatorBuilder
    {
        return new TypeValidatorBuilder();
    }

    public function __construct(
        private Parser $parser,
        private CacheClient $cacheClient
    ) {
    }

    /**
     * @throws CachingException
     * @throws InvalidTypeSpecificationException
     */
    public function isValid(
        string $typeSpecification,
        mixed $value
    ): bool {
        return $this->getTypeTree($typeSpecification)->isValid($value);
    }

    /**
     * @throws CachingException
     * @throws InvalidTypeSpecificationException
     */
    private function getTypeTree(string $typeSpecification): TypeNode
    {
        $cacheEntryId = $this->getCacheEntryId($typeSpecification);

        $typeTree = $this->cacheClient->tryFetch($cacheEntryId);

        if (!$typeTree instanceof TypeNode) {
            $typeTree = $this->parser->parse($typeSpecification);

            $this->cacheClient->store($cacheEntryId, $typeTree);
        }

        return $typeTree;
    }

    private function getCacheEntryId(string $typeSpecification): string
    {
        return self::class . "|typeSpecification:{$typeSpecification}";
    }
}
