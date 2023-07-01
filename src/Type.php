<?php

namespace Jmf\TypeValidation;

use Jmf\TypeValidation\Exception\InvalidTypeException;
use perf\Caching\Exception\CachingException;

/**
 * Static class wrapping a type validator for easier access to type validation.
 */
class Type
{
    private static TypeValidator $validator;

    public static function setValidator(TypeValidator $validator): void
    {
        self::$validator = $validator;
    }

    /**
     * @throws CachingException
     * @throws InvalidTypeException
     */
    public static function mustBe(
        string $typeSpecification,
        mixed $value,
        ?string $name = null
    ): void {
        if (!self::is($typeSpecification, $value)) {
            if (is_string($name)) {
                throw new InvalidTypeException("Provided {$name} does not match type specification.");
            }

            throw new InvalidTypeException('Provided value does not match type specification.');
        }
    }

    /**
     * @throws CachingException
     */
    public static function is(
        string $typeSpecification,
        mixed $value
    ): bool {
        return self::getValidator()->isValid($typeSpecification, $value);
    }

    /**
     * Returns a type validator instance.
     * Lazy getter.
     */
    private static function getValidator(): TypeValidator
    {
        if (empty(self::$validator)) {
            self::setValidator(TypeValidator::createDefault());
        }

        return self::$validator;
    }
}
