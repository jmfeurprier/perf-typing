<?php

namespace Jmf\TypeValidation\Tree;

use PHPUnit\Framework\TestCase;

/**
 * Functional test.
 */
class TreeTest extends TestCase
{
    private TypeNode $tree;

    protected function setUp(): void
    {
        // "{int:string}[]|null|{string:int|float}|{string:{int:float|string}|stdClass}"

        $this->tree = new MultipleTypeNode(
            [
                new CollectionTypeNode(
                    new MapTypeNode(
                        new LeafTypeNode('int'),
                        new LeafTypeNode('string')
                    )
                ),
                new LeafTypeNode('null'),
                new MapTypeNode(
                    new LeafTypeNode('string'),
                    new MultipleTypeNode(
                        [
                            new LeafTypeNode('int'),
                            new LeafTypeNode('float'),
                        ]
                    )
                ),
                new MapTypeNode(
                    new LeafTypeNode('string'),
                    new MultipleTypeNode(
                        [
                            new MapTypeNode(
                                new LeafTypeNode('int'),
                                new MultipleTypeNode(
                                    [
                                        new LeafTypeNode('float'),
                                        new LeafTypeNode('string'),
                                    ]
                                )
                            ),
                            new LeafTypeNode('stdClass'),
                        ]
                    )
                ),
            ]
        );
    }

    /**
     * @return array<mixed[]>
     */
    public static function dataProviderValidValues(): array
    {
        // "{int:string}[]|null|{string:int|float}|{string:{int:float|string}|stdClass}"

        return [
            [[]],
            [[["123" => 'foo']]],
            [null],
            [['foo' => 123]],
            [['foo' => 1.23]],
            [['foo' => [123 => 1.23]]],
            [['foo' => [123 => 'bar']]],
            [['foo' => new \stdClass()]],
        ];
    }

    /**
     * @dataProvider dataProviderValidValues
     */
    public function testWithValidValues(mixed $value): void
    {
        $this->assertTrue($this->tree->isValid($value));
    }

    /**
     * @return array<mixed[]>
     */
    public static function dataProviderInvalidValues(): array
    {
        // "{int:string}[]|null|{string:int|float}|{string:{int:float|string}|stdClass}"

        return [
            [123],
            [1.23],
            [[123 => 234]],
            [['foo' => null]],
        ];
    }

    /**
     * @dataProvider dataProviderInvalidValues
     */
    public function testWithInvalidValues(mixed $value): void
    {
        $this->assertFalse($this->tree->isValid($value));
    }
}
