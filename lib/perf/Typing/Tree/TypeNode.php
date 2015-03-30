<?php

namespace perf\Typing\Tree;

/**
 *
 *
 */
interface TypeNode
{

    /**
     *
     *
     * @param mixed $value
     * @return bool
     */
    public function isValid($value);

    /**
     *
     *
     * @return string
     */
    public function __toString();
}
