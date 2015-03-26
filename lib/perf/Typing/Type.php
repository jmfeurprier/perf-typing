<?php

namespace perf\Typing;

/**
 *
 *
 */
class Type
{

    /**
     *
     *
     * @param string $typeDefinition
     * @param mixed $value
     * @param null|string $name
     * @return bool
     */
    public static function check($typeDefinition, $value, $name = null)
    {
        $validator = self::getValidator();

        if (!$validator->isValid($typeDefinition, $value)) {
            if (null === $name) {
                $message = 'Provided value does not match expected type.';
            } else {
                $message = "Provided {$name} does not match expected type.";
            }

            throw new \InvalidArgumentException($message);
        }
    }

    /**
     *
     *
     * @return TypeValidator
     */
    private static function getValidator()
    {
        static $validator;

        if (!$validator) {
            $validator = new TypeValidator();
        }

        return $validator;
    }
}
