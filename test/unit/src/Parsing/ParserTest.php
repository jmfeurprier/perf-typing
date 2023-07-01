<?php

namespace Jmf\TypeValidation\Parsing;

use Jmf\TypeValidation\Exception\InvalidTypeSpecificationException;
use Jmf\TypeValidation\Tree\TypeNode;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    private Parser $parser;

    protected function setUp(): void
    {
        $this->parser = new Parser(new Tokenizer());
    }

    /**
     * @return array<mixed[]>
     */
    public static function dataProviderValidTypeSpecification(): array
    {
        return [
            ['foo'],
            ['foo|bar'],
            ['foo|bar|baz'],
            ['foo[]'],
            ['foo[][]'],
            ['foo[]|bar[]|baz'],
            ['{string:bar}'],
            ['foo|{string:baz}'],
            ['foo|{string:baz|qux}'],
            ['{string:foo}[]'],
            ['{string:{string:foo|bar[]}|foo|bar[]}[]|foo[]|bar'],
        ];
    }

    /**
     * @dataProvider dataProviderValidTypeSpecification
     */
    public function testParseWithValidTypeSpecification(string $typeSpecification): void
    {
        $result = $this->parser->parse($typeSpecification);

        $this->assertInstanceOf(TypeNode::class, $result);
        $this->assertSame($typeSpecification, (string) $result);
    }

    /**
     * @return array<mixed[]>
     */
    public static function dataProviderInvalidTypeSpecification(): array
    {
        return [
#            [[]],
#            [['null']],
#            [null],
            [''],
            ['|'],
            ['int[]int'],
            ['|null'],
            ['null|'],
            ['null||'],
            ['null||foo'],
            ['null|foo|'],
            ['{float:foo}'],
            ['{[]:foo}'],
            ['{{}:foo}'],
            ['{string'],
            ['{string{'],
            ['{string[]}'],
            ['{string:|}'],
            ['{string:'],
            ['{string:string'],
            ['{string:}'],
            ['{string:string:string}'],
        ];
    }

    /**
     * @dataProvider dataProviderInvalidTypeSpecification
     */
    public function testParseWithInvalidTypeSpecification(string $typeSpecification): void
    {
        $this->expectException(InvalidTypeSpecificationException::class);
        $this->parser->parse($typeSpecification);
    }
}
