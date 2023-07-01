<?php

namespace Jmf\TypeValidation;

use perf\Caching\CacheClient;
use Jmf\TypeValidation\Parsing\Parser;
use PHPUnit\Framework\TestCase;

class TypeValidatorBuilderTest extends TestCase
{
    private TypeValidatorBuilder $typeValidatorBuilder;

    protected function setUp(): void
    {
        $this->typeValidatorBuilder = new TypeValidatorBuilder();
    }

    public function testBuildBare(): void
    {
        $result = $this->typeValidatorBuilder->build();

        $this->assertInstanceOf(TypeValidator::class, $result);
    }

    public function testBuildWithParser(): void
    {
        $parser = $this->createMock(Parser::class);

        $this->typeValidatorBuilder->setParser($parser);

        $result = $this->typeValidatorBuilder->build();

        $this->assertInstanceOf(TypeValidator::class, $result);
    }

    public function testBuildWithCacheClient(): void
    {
        $cacheClient = $this->createMock(CacheClient::class);

        $this->typeValidatorBuilder->setCacheClient($cacheClient);

        $result = $this->typeValidatorBuilder->build();

        $this->assertInstanceOf(TypeValidator::class, $result);
    }
}
