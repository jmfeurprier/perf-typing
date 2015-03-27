<?php

namespace perf\Typing;

/**
 * Static class wrapping a type validator for easy access to type validation.
 *
 */
class Type
{

    /**
     *
     *
     * @var TypeValidator
     */
    private static $validator;

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
            if (is_string($name)) {
                $message = "Provided {$name} does not match expected type.";
            } else {
                $message = 'Provided value does not match expected type.';
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
        if (!self::$validator) {
            self::setValidator(new TypeValidator());
        }

        return self::$validator;
    }

    /**
     *
     *
     * @param TypeValidator $validator
     * @return void
     */
    public static function setValidator(TypeValidator $validator)
    {
        self::$validator = $validator;
    }
}
