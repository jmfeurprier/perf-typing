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
     * Sets a type validator.
     *
     * @param TypeValidator $validator
     * @return void
     */
    public static function setValidator(TypeValidator $validator)
    {
        self::$validator = $validator;
    }

    /**
     *
     *
     * @param string $typeSpecification
     * @param mixed $value
     * @param null|string $name
     * @return void
     * @throws \InvalidArgumentException
     */
    public static function mustBe($typeSpecification, $value, $name = null)
    {
        if (!self::is($typeSpecification, $value)) {
            if (is_string($name)) {
                $message = "Provided {$name} does not match type specification.";
            } else {
                $message = 'Provided value does not match type specification.';
            }

            throw new \InvalidArgumentException($message);
        }
    }

    /**
     *
     *
     * @param string $typeSpecification
     * @param mixed $value
     * @return bool
     */
    public static function is($typeSpecification, $value)
    {
        return self::getValidator()->isValid($typeSpecification, $value);
    }

    /**
     * Returns a type validator instance.
     * Lazy getter.
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
}
