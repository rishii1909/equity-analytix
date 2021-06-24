<?php


namespace App\Entity\Chat\Setting;


use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

/**
 * Class NameType
 *
 * @package   App\Entity\Chat\Setting
 * @author    Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 */
class NameType extends StringType
{
    const NAME = 'chat_setting_name';

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     *
     * @return mixed|string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof Name ? $value->getName() : $value;
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     *
     * @return Name|mixed|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new Name($value) : null;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::NAME;
    }

}