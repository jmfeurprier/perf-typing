<?php

namespace perf\TypeValidation;

/**
 *
 */
class TypeValidatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    protected function setUp()
    {
        $parser = new Parsing\Parser();

        $cacheClient = $this->getMockBuilder('\\perf\\Caching\\CacheClient')->disableOriginalConstructor()->getMock();
        $cacheClient->expects($this->any())->method('tryFetch')->will($this->returnValue(null));
        
        $this->typeValidator = new TypeValidator($parser, $cacheClient);
    }

    /**
     *
     */
    public static function dataProviderInvalidTypeSpecifications()
    {
        return array(
            array(null),
            array(123),
            array(array('int')),
            array('{array:mixed}'),
            array('{resource:mixed}'),
            array('{float:mixed}'),
            array('{double:mixed}'),
            array('{foo:mixed}'),
        );
    }

    /**
     *
     * @dataProvider dataProviderInvalidTypeSpecifications
     * @expectedException \perf\TypeValidation\InvalidTypeSpecificationException
     */
    public function testWithInvalidTypeSpecificationWillThrowException($typeSpecification)
    {
        $this->typeValidator->isValid($typeSpecification, null);
    }

    /**
     *
     */
    public static function dataProviderBaseTypesValidCases()
    {
        $booleanTrue  = true;
        $booleanFalse = false;
        $integerZero  = 0;
        $integer      = 123;
        $floatZero    = 0.0;
        $float        = 2.34;
        $stringEmpty  = '';
        $string       = 'foo';
        $arrayEmpty   = array();
        $arrayFilled  = array(123, 'foo');
        $stdClass     = new \stdClass();

        return array(
            array('bool',       $booleanTrue),
            array('bool',       $booleanFalse),
            array('boolean',    $booleanTrue),
            array('boolean',    $booleanFalse),

            array('int',        $integerZero),
            array('int',        $integer),
            array('int',        $integer),
            array('integer',    $integerZero),
            array('integer',    $integer),
            array('integer',    $integer),

            array('double',     $floatZero),
            array('double',     $float),
            array('float',      $floatZero),
            array('float',      $float),

            array('string',     $stringEmpty),
            array('string',     $string),

            array('array',      $arrayEmpty),
            array('array',      $arrayFilled),

            array('object',     $stdClass),
            array('object',     $stdClass),
#            array('stdClass',   $stdClass),
#            array('\\stdClass', $stdClass),

            array('mixed',      $booleanTrue),
            array('mixed',      $booleanFalse),
            array('mixed',      $integerZero),
            array('mixed',      $integer),
            array('mixed',      $floatZero),
            array('mixed',      $float),
            array('mixed',      $stringEmpty),
            array('mixed',      $string),
            array('mixed',      $stdClass),
            array('mixed',      $arrayEmpty),
            array('mixed',      $arrayFilled),
            array('mixed',      $stdClass),
        );
    }

    /**
     *
     * @dataProvider dataProviderBaseTypesValidCases
     */
    public function testBaseTypesWithValidValues($typeSpecification, $value)
    {
        $this->executeValidTestCase($typeSpecification, $value);
    }

    /**
     *
     */
    public static function dataProviderBaseTypesInvalidValues()
    {
        $int      = 123;
        $float    = 2.34;
        $string   = 'foo';
        $stdClass = new \stdClass();

        return array(
            array('int',      $float),
            array('int',      $string),
            array('int',      $stdClass),

            array('float',    $int),
            array('float',    $string),
            array('float',    $stdClass),

            array('string',   $int),
            array('string',   $float),
            array('string',   $stdClass),

            array('stdClass', $int),
            array('stdClass', $float),
            array('stdClass', $string),
        );
    }

    /**
     *
     * @dataProvider dataProviderBaseTypesInvalidValues
     */
    public function testBaseTypesWithInvalidValues($typeSpecification, $value)
    {
        $this->executeInvalidTestCase($typeSpecification, $value);
    }

    /**
     *
     */
    public static function dataProviderNonIndexedArrayValidCases()
    {
        return array(
            array('string[]', array()),
            array('string[]', array('foo')),
            array('string[]', array('foo' => 'bar')),

            array('int[]', array()),
            array('int[]', array(123)),
            array('int[]', array('foo' => 123)),
            array('int[]', array('foo' => -123)),

            array('float[]', array()),
            array('float[]', array(1.23)),
            array('float[]', array('foo' => 1.23)),
            array('float[]', array('foo' => -1.23)),
        );
    }

    /**
     *
     * @dataProvider dataProviderNonIndexedArrayValidCases
     */
    public function testNonIndexedArrayTypeWithValidValues($typeSpecification, $value)
    {
        $this->executeValidTestCase($typeSpecification, $value);
    }

    /**
     *
     */
    public static function dataProviderNonIndexedArrayInvalidCases()
    {
        return array(
            array('string[]', 'foo'),
            array('string[]', array(123)),

            array('int[]', 123),
            array('int[]', array(1.23)),

            array('float[]', 1.23),
            array('float[]', array(123)),
        );
    }

    /**
     *
     * @dataProvider dataProviderNonIndexedArrayInvalidCases
     */
    public function testNonIndexedArrayTypeWithInvalidValues($typeSpecification, $value)
    {
        $this->executeInvalidTestCase($typeSpecification, $value);
    }

    /**
     *
     */
    public static function dataProviderIndexedArrayValidCases()
    {
        return array(
            array('{string:mixed}', array()),
            array('{string:mixed}', array('foo' => 'bar')),
        );
    }

    /**
     *
     * @dataProvider dataProviderIndexedArrayValidCases
     */
    public function testIndexedArrayTypeWithValidValues($typeSpecification, $value)
    {
        $this->executeValidTestCase($typeSpecification, $value);
    }

    /**
     *
     */
    public static function dataProviderIndexedArrayInvalidCases()
    {
        return array(
            array('{mixed:mixed}', 'foo'),

            array('{int:mixed}', array('foo' => 'bar')),
            array('{int:string}', array('foo' => 'bar')),
            array('{int:int}', array('foo' => 123)),

            array('{string:mixed}', array(123 => 'foo')),
            array('{string:string}', array(123 => 'foo')),
            array('{string:int}', array(123 => 234)),

            array('{mixed:int}', array('foo')),
            array('{mixed:int}', array('foo' => 'bar')),
            array('{mixed:int}', array(123 => 'bar')),

            array('{mixed:string}', array(123)),
            array('{mixed:string}', array('foo' => 123)),
            array('{mixed:string}', array(123 => 456)),
        );
    }

    /**
     *
     * @dataProvider dataProviderIndexedArrayInvalidCases
     */
    public function testIndexedArrayTypeWithInvalidValues($typeSpecification, $value)
    {
        $this->executeInvalidTestCase($typeSpecification, $value);
    }

    /**
     *
     */
    public static function dataProviderValidAlternativeTypeSpecifications()
    {
        return array(
            array('mixed|mixed', 123),
            array('mixed|mixed', 'foo'),
            array('mixed|mixed', array()),

            array('mixed|int', 123),
            array('mixed|string', 'foo'),
            array('mixed|array', array()),

            array('string|int', 'foo'),
            array('string|int', 123),
            array('string|array', 'foo'),
            array('string|array', array()),

            array('int|string', 123),
            array('int|string', 'foo'),
        );
    }

    /**
     *
     * @dataProvider dataProviderValidAlternativeTypeSpecifications
     */
    public function testWithValidAlternativeTypeSpecifications($typeSpecification, $value)
    {
        $this->typeValidator->isValid($typeSpecification, $value);
    }

    /**
     *
     */
    private function executeValidTestCase($typeSpecification, $value)
    {
        $result = $this->typeValidator->isValid($typeSpecification, $value);

        $this->assertTrue($result, "Type specification '{$typeSpecification}' is not satisfied by provided value.");
    }

    /**
     *
     */
    private function executeInvalidTestCase($typeSpecification, $value)
    {
        $result = $this->typeValidator->isValid($typeSpecification, $value);

        $this->assertFalse($result, "Type specification '{$typeSpecification}' should not be satisfied by provided value.");
    }
}
