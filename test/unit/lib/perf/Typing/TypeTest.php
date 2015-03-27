<?php

namespace perf\Typing;

/**
 *
 */
class TypeTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function testWithValidValue()
    {
        Type::mustBe('string', 'string');
    }

    /**
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Provided value does not match type specification.
     */
    public function testWithInvalidValueWillThrowException()
    {
        Type::mustBe('integer', 'string');
    }

    /**
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Provided foo does not match type specification.
     */
    public function testWithInvalidValueAndNamedValueWillThrowException()
    {
        Type::mustBe('integer', 'string', 'foo');
    }
}
