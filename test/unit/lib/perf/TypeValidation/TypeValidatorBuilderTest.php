<?php

namespace perf\TypeValidation;

/**
 *
 */
class TypeValidatorBuilderTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    protected function setUp()
    {
        $this->typeValidatorBuilder = new TypeValidatorBuilder();
    }

    /**
     *
     */
    public function testBuildBare()
    {
        $result = $this->typeValidatorBuilder->build();

        $this->assertInstanceOf('\\perf\\TypeValidation\\TypeValidator', $result);
    }

    /**
     *
     */
    public function testBuildWithParser()
    {
        $parser = $this->getMock('\\perf\\TypeValidation\\Parsing\\Parser');

        $this->typeValidatorBuilder->setParser($parser);

        $result = $this->typeValidatorBuilder->build();

        $this->assertInstanceOf('\\perf\\TypeValidation\\TypeValidator', $result);
    }

    /**
     *
     */
    public function testBuildWithCacheClient()
    {
        $cacheClient = $this->getMockBuilder('\\perf\Caching\CacheClient')->disableOriginalConstructor()->getMock();

        $this->typeValidatorBuilder->setCacheClient($cacheClient);

        $result = $this->typeValidatorBuilder->build();

        $this->assertInstanceOf('\\perf\\TypeValidation\\TypeValidator', $result);
    }
}
