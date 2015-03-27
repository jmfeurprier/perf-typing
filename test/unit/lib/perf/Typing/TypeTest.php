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
     */
    public function testWithInvalidValueWillThrowException()
    {
        Type::check('integer', 'string');
    }
}
