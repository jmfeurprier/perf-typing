<?php

namespace perf\TypeValidation\Parsing;

/**
 *
 */
class ParserTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    protected function setUp()
    {
        $this->parser = new Parser();
    }

    /**
     *
     */
    public static function dataProviderValidTypeSpecification()
    {
        return array(
            array('foo'),
            array('foo|bar'),
            array('foo|bar|baz'),
            array('foo[]'),
            array('foo[][]'),
            array('foo[]|bar[]|baz'),
            array('{string:bar}'),
            array('foo|{string:baz}'),
            array('foo|{string:baz|qux}'),
            array('{string:foo}[]'),
            array('{string:{string:foo|bar[]}|foo|bar[]}[]|foo[]|bar'),
        );
    }

    /**
     *
     * @dataProvider dataProviderValidTypeSpecification
     */
    public function testParseWithValidTypeSpecification($typeSpecification)
    {
        $result = $this->parser->parse($typeSpecification);

        $this->assertInstanceOf('\\perf\\TypeValidation\\Tree\\TypeNode', $result);
        $this->assertSame($typeSpecification, (string) $result);
    }

    /**
     *
     */
    public static function dataProviderInvalidTypeSpecification()
    {
        return array(
            array(array()),
            array(array('null')),
            array(null),
            array(''),
            array('|'),
            array('int[]int'),
            array('|null'),
            array('null|'),
            array('null||'),
            array('null||foo'),
            array('null|foo|'),
            array('{float:foo}'),
            array('{[]:foo}'),
            array('{{}:foo}'),
            array('{string'),
            array('{string{'),
            array('{string[]}'),
            array('{string:|}'),
            array('{string:'),
            array('{string:string'),
            array('{string:}'),
            array('{string:string:string}'),
        );
    }

    /**
     *
     * @dataProvider dataProviderInvalidTypeSpecification
     * @expectedException \perf\TypeValidation\InvalidTypeSpecificationException
     */
    public function testParseWithInvalidTypeSpecification($typeSpecification)
    {
        $this->parser->parse($typeSpecification);
    }
}
