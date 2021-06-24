<?php


namespace App\Entity\Chat\Setting;


use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

/**
 * Class SignificationType
 *
 * @package   App\Entity\Chat\Setting
 * @author    Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 */
class SignificationType extends StringType
{

    const NAME = 'chat_setting_signification';

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     *
     * @return mixed|string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof Signification ? $value->getValue() : $value;
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     *
     * @return Signification|mixed|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new Signification($value) : null;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::NAME;
    }
}