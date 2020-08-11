<?php

namespace perf\TypeValidation;

use perf\Caching\CacheClient;
use perf\Caching\Storage\VolatileCachingStorage;
use perf\TypeValidation\Parsing\Parser;
use perf\TypeValidation\Parsing\Tokenizer;

class TypeValidatorBuilder
{
    private Parser $parser;

    private CacheClient $cacheClient;

    public function setParser(Parser $parser): self
    {
        $this->parser = $parser;

        return $this;
    }

    public function setCacheClient(CacheClient $cacheClient): self
    {
        $this->cacheClient = $cacheClient;

        return $this;
    }

    public function build(): TypeValidator
    {
        return new TypeValidator($this->getParser(), $this->getCacheClient());
    }

    private function getParser(): Parser
    {
        return $this->parser ?? new Parser(new Tokenizer());
    }

    private function getCacheClient(): CacheClient
    {
        if (!empty($this->cacheClient)) {
            return $this->cacheClient;
        }

        $storage = new VolatileCachingStorage();

        return CacheClient::createBuilder()->setStorage($storage)->build();
    }
}
