<?php

namespace perf\Typing;

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
        $this->typeValidator = new TypeValidator();
    }

    /**
     *
     */
    public function dataProviderValidCases()
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
     * @dataProvider dataProviderValidCases
     */
    public function testWithValidValues($typeDefinition, $value)
    {
        $this->assertTrue($this->typeValidator->isValid($typeDefinition, $value));
    }

    /**
     *
     */
    public function dataProviderInvalidValues()
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
     * @dataProvider dataProviderInvalidValues
     */
    public function testWithInvalidValuesWillThrowException($typeDefinition, $value)
    {
        $this->assertFalse($this->typeValidator->isValid($typeDefinition, $value));
    }
}
