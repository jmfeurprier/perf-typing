<?php

namespace perf\TypeValidation;

use perf\TypeValidation\Exception\InvalidTypeException;

/**
 * Static class wrapping a type validator for easy access to type validation.
 */
class Type
{
    private static TypeValidator $validator;

    public static function setValidator(TypeValidator $validator): void
    {
        self::$validator = $validator;
    }

    /**
     * @param string      $typeSpecification
     * @param mixed       $value
     * @param null|string $name
     *
     * @return void
     *
     * @throws InvalidTypeException
     */
    public static function mustBe(string $typeSpecification, $value, ?string $name = null): void
    {
        if (!self::is($typeSpecification, $value)) {
            if (is_string($name)) {
                throw new InvalidTypeException("Provided {$name} does not match type specification.");
            }

            throw new InvalidTypeException('Provided value does not match type specification.');
        }
    }

    public static function is(string $typeSpecification, $value): bool
    {
        return self::getValidator()->isValid($typeSpecification, $value);
    }

    /**
     * Returns a type validator instance.
     * Lazy getter.
     *
     * @return TypeValidator
     */
    private static function getValidator(): TypeValidator
    {
        if (empty(self::$validator)) {
            self::setValidator(TypeValidator::createDefault());
        }

        return self::$validator;
    }
}
