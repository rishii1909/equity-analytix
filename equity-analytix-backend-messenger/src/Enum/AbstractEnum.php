<?php
declare(strict_types = 1);

namespace App\Enum;

/**
 * Class AbstractEnum
*/
abstract class AbstractEnum
{
    /**
     * @return array
     * @throws \ReflectionException An \ReflectionException instance.
     */
    public static function getPossibleValues(): array
    {
        return array_values((new \ReflectionClass(static::class))->getConstants());
    }

    /**
     * @return array
     * @throws \ReflectionException An \ReflectionException instance.
     */
    public static function getPossibleValuesAssoc(): array
    {
        $values = static::getPossibleValues();

        return array_combine($values, $values);
    }

    /**
     * @param string $value
     *
     * @return boolean
     * @throws \ReflectionException An \ReflectionException instance.
     */
    public static function checkValue(string $value): bool
    {
        return in_array($value, static::getPossibleValues(), true);
    }
}
