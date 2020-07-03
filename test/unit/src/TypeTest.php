<?php

namespace perf\TypeValidation;

use perf\TypeValidation\Exception\InvalidTypeException;
use PHPUnit\Framework\TestCase;

class TypeTest extends TestCase
{
    public function testWithValidValue()
    {
        Type::mustBe('string', 'string');

        $this->assertTrue(true);
    }

    public function testWithInvalidValueWillThrowException()
    {
        $this->expectException(InvalidTypeException::class);
        $this->expectExceptionMessage('Provided value does not match type specification.');

        Type::mustBe('integer', 'string');
    }

    public function testWithInvalidValueAndNamedValueWillThrowException()
    {
        $this->expectException(InvalidTypeException::class);
        $this->expectExceptionMessage('Provided foo does not match type specification.');

        Type::mustBe('integer', 'string', 'foo');
    }
}
