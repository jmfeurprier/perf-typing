<?php

namespace perf\Typing;

use perf\Typing\Parsing\Parser;
use perf\Caching\CacheClient;
use perf\Caching\VolatileStorage;

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
     * @param Parser $parser
     * @return void
     */
    public function setParser(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     *
     *
     * @param CacheClient $cacheClient
     * @return void
     */
    public function setCacheClient(CacheClient $cacheClient)
    {
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

        $typeTree = $this->getCacheClient()->tryFetch($cacheEntryId);

        if (!$typeTree) {
            $typeTree = $this->getParser()->parse($typeSpecification);

            $this->getCacheClient()->store($cacheEntryId, $typeTree);
        }

        return $typeTree;
    }

    /**
     *
     *
     * @return Parser
     */
    private function getParser()
    {
        if (!$this->parser) {
            $this->setParser(new Parser());
        }

        return $this->parser;
    }

    /**
     *
     *
     * @return CacheClient
     */
    private function getCacheClient()
    {
        if (!$this->cacheClient) {
            $cacheStorage = new VolatileStorage();
            $cacheClient  = new CacheClient($cacheStorage);

            $this->setCacheClient($cacheClient);
        }

        return $this->cacheClient;
    }
}
