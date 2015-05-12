<?php

namespace perf\TypeValidation;

use perf\Caching\CacheClient;
use perf\Caching\VolatileStorage;
use perf\TypeValidation\Parsing\Parser;

/**
 *
 *
 */
class TypeValidatorBuilder
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
     * @return TypeValidatorBuilder Fluent return.
     */
    public function setParser(Parser $parser)
    {
        $this->parser = $parser;

        return $this;
    }

    /**
     *
     *
     * @param CacheClient $cacheClient
     * @return TypeValidatorBuilder Fluent return.
     */
    public function setCacheClient(CacheClient $cacheClient)
    {
        $this->cacheClient = $cacheClient;

        return $this;
    }

    /**
     *
     *
     * @return TypeValidator
     */
    public function build()
    {
        return new TypeValidator($this->getParser(), $this->getCacheClient());
    }

    /**
     *
     *
     * @return Parser
     */
    private function getParser()
    {
        if ($this->parser) {
            return $this->parser;
        }

        return new Parser();
    }

    /**
     *
     *
     * @return CacheClient
     */
    private function getCacheClient()
    {
        if ($this->cacheClient) {
            return $this->cacheClient;
        }

        $cacheStorage = new VolatileStorage();

        return new CacheClient($cacheStorage);
    }
}
