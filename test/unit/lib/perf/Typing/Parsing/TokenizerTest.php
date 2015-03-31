<?php

namespace perf\Typing\Parsing;

/**
 *
 */
class TokenizerTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    protected function setUp()
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

        $expected = array(
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
        );

        $this->assertInternalType('array', $result);
        $this->assertCount(count($expected), $result);
        $this->assertContainsOnly('\\perf\\Typing\\Parsing\\Token', $result);

        $index = 0;
        foreach ($expected as $expectedOffset => $expectedContent) {
            $resultToken = $result[$index];

            $this->assertSame($expectedContent, $resultToken->getContent());
            $this->assertSame($expectedOffset, $resultToken->getOffset());

            ++$index;
        }
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
            array('*'),
            array(' string'),
            array('string '),
            array('string*'),
            array('string[]*'),
        );
    }

    /**
     *
     * @dataProvider dataProviderInvalidTypeSpecification
     * @expectedException \perf\Typing\InvalidTypeSpecificationException
     */
    public function testTokenizeWithInvalidTypeSpecificationWillThrowException($typeSpecification)
    {
        $this->tokenizer->tokenize($typeSpecification);
    }
}
