<?php

namespace perf\TypeValidation\Parsing;

use perf\TypeValidation\Exception\InvalidTypeSpecificationException;
use PHPUnit\Framework\TestCase;

class TokenizerTest extends TestCase
{
    private Tokenizer $tokenizer;

    protected function setUp(): void
    {
        $this->tokenizer = new Tokenizer();
    }

    /**
     *
     */
    public function testTokenizeWithValidTypeSpecification()
    {
        $typeSpecification = "{int:string}[]|null|{string:int|\\Foo\\Bar}|{string:{int:Foo\\Bar|string}|Baz}";

        $result = $this->tokenizer->tokenize($typeSpecification);

        $expected = [
            0  => "{",
            1  => "int",
            4  => ":",
            5  => "string",
            11 => "}",
            12 => "[]",
            14 => "|",
            15 => "null",
            19 => "|",
            20 => "{",
            21 => "string",
            27 => ":",
            28 => "int",
            31 => "|",
            32 => "\\Foo\\Bar",
            40 => "}",
            41 => "|",
            42 => "{",
            43 => "string",
            49 => ":",
            50 => "{",
            51 => "int",
            54 => ":",
            55 => "Foo\\Bar",
            62 => "|",
            63 => "string",
            69 => "}",
            70 => "|",
            71 => "Baz",
            74 => "}",
        ];

        $this->assertIsArray($result);
        $this->assertCount(count($expected), $result);
        $this->assertContainsOnly('\\perf\\TypeValidation\\Parsing\\Token', $result);

        $index = 0;
        foreach ($expected as $expectedOffset => $expectedContent) {
            $resultToken = $result[$index];

            $this->assertSame($expectedContent, $resultToken->getContent());
            $this->assertSame($expectedOffset, $resultToken->getOffset());

            ++$index;
        }
    }

    public static function dataProviderInvalidTypeSpecification()
    {
        return [
#            [[]],
#            [['null']],
#            [null],
            [''],
            ['*'],
            [' string'],
            ['string '],
            ['string*'],
            ['string[]*'],
        ];
    }

    /**
     * @dataProvider dataProviderInvalidTypeSpecification
     */
    public function testTokenizeWithInvalidTypeSpecificationWillThrowException($typeSpecification)
    {
        $this->expectException(InvalidTypeSpecificationException::class);

        $this->tokenizer->tokenize($typeSpecification);
    }
}
