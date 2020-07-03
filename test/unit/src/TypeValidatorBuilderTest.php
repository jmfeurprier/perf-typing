<?php

namespace perf\TypeValidation;

use perf\Caching\CacheClient;
use perf\TypeValidation\Parsing\Parser;
use PHPUnit\Framework\TestCase;

class TypeValidatorBuilderTest extends TestCase
{
    private TypeValidatorBuilder $typeValidatorBuilder;

    protected function setUp(): void
    {
        $this->typeValidatorBuilder = new TypeValidatorBuilder();
    }

    public function testBuildBare()
    {
        $result = $this->typeValidatorBuilder->build();

        $this->assertInstanceOf(TypeValidator::class, $result);
    }

    public function testBuildWithParser()
    {
        $parser = $this->createMock(Parser::class);

        $this->typeValidatorBuilder->setParser($parser);

        $result = $this->typeValidatorBuilder->build();

        $this->assertInstanceOf(TypeValidator::class, $result);
    }

    public function testBuildWithCacheClient()
    {
        $cacheClient = $this->createMock(CacheClient::class);

        $this->typeValidatorBuilder->setCacheClient($cacheClient);

        $result = $this->typeValidatorBuilder->build();

        $this->assertInstanceOf(TypeValidator::class, $result);
    }
}
