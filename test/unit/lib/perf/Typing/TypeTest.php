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
        Type::check('string', 'string');
    }

    /**
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Provided value does not match expected type.
     */
    public function testWithInvalidValueWillThrowException()
    {
        Type::check('integer', 'string');
    }

    /**
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Provided foo does not match expected type.
     */
    public function testWithInvalidValueAndNamedValueWillThrowException()
    {
        Type::check('integer', 'string', 'foo');
    }
}
