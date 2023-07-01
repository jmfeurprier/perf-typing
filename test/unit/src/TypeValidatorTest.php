<?php

namespace Jmf\TypeValidation;

use perf\Caching\CacheClient;
use Jmf\TypeValidation\Exception\InvalidTypeSpecificationException;
use Jmf\TypeValidation\Parsing\Parser;
use Jmf\TypeValidation\Parsing\Tokenizer;
use PHPUnit\Framework\TestCase;

class TypeValidatorTest extends TestCase
{
    private TypeValidator $typeValidator;

    protected function setUp(): void
    {
        $parser = new Parser(new Tokenizer());

        $cacheClient = $this->createMock(CacheClient::class);
        $cacheClient->expects($this->any())->method('tryFetch')->willReturn(null);

        $this->typeValidator = new TypeValidator($parser, $cacheClient);
    }

    /**
     * @return array<mixed[]>
     */
    public static function dataProviderInvalidTypeSpecifications(): array
    {
        return [
            ['{array:mixed}'],
            ['{resource:mixed}'],
            ['{float:mixed}'],
            ['{double:mixed}'],
            ['{foo:mixed}'],
        ];
    }

    /**
     * @dataProvider dataProviderInvalidTypeSpecifications
     */
    public function testWithInvalidTypeSpecificationWillThrowException(string $typeSpecification): void
    {
        $this->expectException(InvalidTypeSpecificationException::class);

        $this->typeValidator->isValid($typeSpecification, null);
    }

    /**
     * @return array<mixed[]>
     */
    public static function dataProviderBaseTypesValidCases(): array
    {
        $booleanTrue  = true;
        $booleanFalse = false;
        $integerZero  = 0;
        $integer      = 123;
        $floatZero    = 0.0;
        $float        = 2.34;
        $stringEmpty  = '';
        $string       = 'foo';
        $arrayEmpty   = [];
        $arrayFilled  = [123, 'foo'];
        $stdClass     = new \stdClass();

        return [
            ['bool', $booleanTrue],
            ['bool', $booleanFalse],
            ['boolean', $booleanTrue],
            ['boolean', $booleanFalse],

            ['int', $integerZero],
            ['int', $integer],
            ['int', $integer],
            ['integer', $integerZero],
            ['integer', $integer],
            ['integer', $integer],

            ['double', $floatZero],
            ['double', $float],
            ['float', $floatZero],
            ['float', $float],

            ['string', $stringEmpty],
            ['string', $string],

            ['array', $arrayEmpty],
            ['array', $arrayFilled],

            ['object', $stdClass],
            ['object', $stdClass],
#            array('stdClass',   $stdClass),
#            array('\\stdClass', $stdClass),

            ['mixed', $booleanTrue],
            ['mixed', $booleanFalse],
            ['mixed', $integerZero],
            ['mixed', $integer],
            ['mixed', $floatZero],
            ['mixed', $float],
            ['mixed', $stringEmpty],
            ['mixed', $string],
            ['mixed', $stdClass],
            ['mixed', $arrayEmpty],
            ['mixed', $arrayFilled],
            ['mixed', $stdClass],
        ];
    }

    /**
     * @dataProvider dataProviderBaseTypesValidCases
     */
    public function testBaseTypesWithValidValues(string $typeSpecification, mixed $value): void
    {
        $this->executeValidTestCase($typeSpecification, $value);
    }

    /**
     * @return array<mixed[]>
     */
    public static function dataProviderBaseTypesInvalidValues(): array
    {
        $int      = 123;
        $float    = 2.34;
        $string   = 'foo';
        $stdClass = new \stdClass();

        return [
            ['int', $float],
            ['int', $string],
            ['int', $stdClass],

            ['float', $int],
            ['float', $string],
            ['float', $stdClass],

            ['string', $int],
            ['string', $float],
            ['string', $stdClass],

            ['stdClass', $int],
            ['stdClass', $float],
            ['stdClass', $string],
        ];
    }

    /**
     * @dataProvider dataProviderBaseTypesInvalidValues
     */
    public function testBaseTypesWithInvalidValues(string $typeSpecification, mixed $value): void
    {
        $this->executeInvalidTestCase($typeSpecification, $value);
    }

    /**
     * @return array<mixed[]>
     */
    public static function dataProviderNonIndexedArrayValidCases(): array
    {
        return [
            ['string[]', []],
            ['string[]', ['foo']],
            ['string[]', ['foo' => 'bar']],

            ['int[]', []],
            ['int[]', [123]],
            ['int[]', ['foo' => 123]],
            ['int[]', ['foo' => -123]],

            ['float[]', []],
            ['float[]', [1.23]],
            ['float[]', ['foo' => 1.23]],
            ['float[]', ['foo' => -1.23]],
        ];
    }

    /**
     * @dataProvider dataProviderNonIndexedArrayValidCases
     */
    public function testNonIndexedArrayTypeWithValidValues(string $typeSpecification, mixed $value): void
    {
        $this->executeValidTestCase($typeSpecification, $value);
    }

    /**
     * @return array<mixed[]>
     */
    public static function dataProviderNonIndexedArrayInvalidCases(): array
    {
        return [
            ['string[]', 'foo'],
            ['string[]', [123]],

            ['int[]', 123],
            ['int[]', [1.23]],

            ['float[]', 1.23],
            ['float[]', [123]],
        ];
    }

    /**
     * @dataProvider dataProviderNonIndexedArrayInvalidCases
     */
    public function testNonIndexedArrayTypeWithInvalidValues(string $typeSpecification, mixed $value): void
    {
        $this->executeInvalidTestCase($typeSpecification, $value);
    }

    /**
     * @return array<mixed[]>
     */
    public static function dataProviderIndexedArrayValidCases(): array
    {
        return [
            ['{string:mixed}', []],
            ['{string:mixed}', ['foo' => 'bar']],
        ];
    }

    /**
     * @dataProvider dataProviderIndexedArrayValidCases
     */
    public function testIndexedArrayTypeWithValidValues(string $typeSpecification, mixed $value): void
    {
        $this->executeValidTestCase($typeSpecification, $value);
    }

    /**
     * @return array<mixed[]>
     */
    public static function dataProviderIndexedArrayInvalidCases(): array
    {
        return [
            ['{mixed:mixed}', 'foo'],

            ['{int:mixed}', ['foo' => 'bar']],
            ['{int:string}', ['foo' => 'bar']],
            ['{int:int}', ['foo' => 123]],

            ['{string:mixed}', [123 => 'foo']],
            ['{string:string}', [123 => 'foo']],
            ['{string:int}', [123 => 234]],

            ['{mixed:int}', ['foo']],
            ['{mixed:int}', ['foo' => 'bar']],
            ['{mixed:int}', [123 => 'bar']],

            ['{mixed:string}', [123]],
            ['{mixed:string}', ['foo' => 123]],
            ['{mixed:string}', [123 => 456]],
        ];
    }

    /**
     * @dataProvider dataProviderIndexedArrayInvalidCases
     */
    public function testIndexedArrayTypeWithInvalidValues(string $typeSpecification, mixed $value): void
    {
        $this->executeInvalidTestCase($typeSpecification, $value);
    }

    /**
     * @return array<mixed[]>
     */
    public static function dataProviderValidAlternativeTypeSpecifications(): array
    {
        return [
            ['mixed|mixed', 123],
            ['mixed|mixed', 'foo'],
            ['mixed|mixed', []],

            ['mixed|int', 123],
            ['mixed|string', 'foo'],
            ['mixed|array', []],

            ['string|int', 'foo'],
            ['string|int', 123],
            ['string|array', 'foo'],
            ['string|array', []],

            ['int|string', 123],
            ['int|string', 'foo'],
        ];
    }

    /**
     * @dataProvider dataProviderValidAlternativeTypeSpecifications
     */
    public function testWithValidAlternativeTypeSpecifications(string $typeSpecification, mixed $value): void
    {
        $this->typeValidator->isValid($typeSpecification, $value);

        $this->assertTrue(true);
    }

    private function executeValidTestCase(string $typeSpecification, mixed $value): void
    {
        $result = $this->typeValidator->isValid($typeSpecification, $value);

        $this->assertTrue(
            $result,
            "Type specification '{$typeSpecification}' is not satisfied by provided value."
        );
    }

    private function executeInvalidTestCase(string $typeSpecification, mixed $value): void
    {
        $result = $this->typeValidator->isValid($typeSpecification, $value);

        $this->assertFalse(
            $result,
            "Type specification '{$typeSpecification}' should not be satisfied by provided value."
        );
    }
}
