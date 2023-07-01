<?php

namespace Jmf\TypeValidation;

use Jmf\TypeValidation\Exception\InvalidTypeException;
use PHPUnit\Framework\TestCase;

class TypeTest extends TestCase
{
    public function testWithValidValue(): void
    {
        Type::mustBe('string', 'string');

        $this->assertTrue(true);
    }

    public function testWithInvalidValueWillThrowException(): void
    {
        $this->expectException(InvalidTypeException::class);
        $this->expectExceptionMessage('Provided value does not match type specification.');

        Type::mustBe('integer', 'string');
    }

    public function testWithInvalidValueAndNamedValueWillThrowException(): void
    {
        $this->expectException(InvalidTypeException::class);
        $this->expectExceptionMessage('Provided foo does not match type specification.');

        Type::mustBe('integer', 'string', 'foo');
    }
}
