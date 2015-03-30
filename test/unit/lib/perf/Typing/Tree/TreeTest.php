<?php

namespace perf\Typing\Tree;

/**
 *
 * Functional test.
 */
class TreeTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    protected function setUp()
    {
        // "{int:string}[]|null|{string:int|float}|{string:{int:float|string}|stdClass}"

        $this->tree = new MultipleTypeNode(
            array(
                new NonIndexedArrayTypeNode(
                    new IndexedArrayTypeNode(
                        new LeafTypeNode('int'),
                        new LeafTypeNode('string')
                    )
                ),
                new LeafTypeNode('null'),
                new IndexedArrayTypeNode(
                    new LeafTypeNode('string'),
                    new MultipleTypeNode(
                        array(
                            new LeafTypeNode('int'),
                            new LeafTypeNode('float'),
                        )
                    )
                ),
                new IndexedArrayTypeNode(
                    new LeafTypeNode('string'),
                    new MultipleTypeNode(
                        array(
                            new IndexedArrayTypeNode(
                                new LeafTypeNode('int'),
                                new MultipleTypeNode(
                                    array(
                                        new LeafTypeNode('float'),
                                        new LeafTypeNode('string'),
                                    )
                                )
                            ),
                            new LeafTypeNode('stdClass'),
                        )
                    )
                ),
            )
        );
    }

    /**
     *
     */
    public static function dataProviderValidValues()
    {
        // "{int:string}[]|null|{string:int|float}|{string:{int:float|string}|stdClass}"

        return array(
            array(array()),
            array(array(array("123" => 'foo'))),
            array(null),
            array(array('foo' => 123)),
            array(array('foo' => 1.23)),
            array(array('foo' => array(123 => 1.23))),
            array(array('foo' => array(123 => 'bar'))),
            array(array('foo' => new \stdClass())),
        );
    }

    /**
     *
     * @dataProvider dataProviderValidValues
     */
    public function testWithValidValues($value)
    {
        $this->assertTrue($this->tree->isValid($value));
    }

    /**
     *
     */
    public static function dataProviderInvalidValues()
    {
        // "{int:string}[]|null|{string:int|float}|{string:{int:float|string}|stdClass}"

        return array(
            array(123),
            array(1.23),
            array(array(123 => 234)),
            array(array('foo' => null)),
        );
    }

    /**
     *
     * @dataProvider dataProviderInvalidValues
     */
    public function testWithInvalidValues($value)
    {
        $this->assertFalse($this->tree->isValid($value));
    }
}
