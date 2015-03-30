<?php

namespace perf\Typing\Tokenization;

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
            "{",
            "int",
            ":",
            "string",
            "}",
            "[]",
            "|",
            "null",
            "|",
            "{",
            "string",
            ":",
            "int",
            "|",
            "\\Foo\\Bar",
            "}",
            "|",
            "{",
            "string",
            ":",
            "{",
            "int",
            ":",
            "Foo\\Bar",
            "|",
            "string",
            "}",
            "|",
            "Baz",
            "}",
        );

        $this->assertInternalType('array', $result);
        $this->assertCount(count($expected), $result);
        $this->assertContainsOnly('\\perf\\Typing\\Tokenization\\Token', $result);

        $index = 0;
        foreach ($result as $resultToken) {
            $expectedTokenContent = $expected[$index];

            $this->assertSame($expectedTokenContent, $resultToken->getContent());

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
     * @expectedException \perf\Typing\Exception\InvalidTypeSpecificationException
     */
    public function testTokenizeWithInvalidTypeSpecificationWillThrowException($typeSpecification)
    {
        $this->tokenizer->tokenize($typeSpecification);
    }
}
