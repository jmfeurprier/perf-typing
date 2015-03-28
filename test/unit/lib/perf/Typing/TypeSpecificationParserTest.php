<?php

namespace perf\Typing;

/**
 *
 */
class TypeSpecificationParserTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    protected function setUp()
    {
        $this->typeSpecificationParser = new TypeSpecificationParser();
    }

    /**
     *
     */
    public static function dataProviderParse()
    {
        return array(
            array('foo', array('foo')),
            array('foo|bar', array('foo', 'bar')),
            array('foo|{bar:baz|qux}|abc', array('foo', '{bar:baz|qux}', 'abc')),
        );
    }

    /**
     *
     * @dataProvider dataProviderParse
     */
    public function testParse($typeSpecification, $expected)
    {
        $result = $this->typeSpecificationParser->parse($typeSpecification);

        $this->assertSame($expected, $result);
    }
}
