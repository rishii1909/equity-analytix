<?php


namespace App\Entity\Chat\User;


use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class RoleType extends StringType
{
    public const NAME = 'chat_user_role';

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return mixed|string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof Role ? $value->getValue() : $value;
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     *
     * @return Role|mixed|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new Role($value) : null;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::NAME;
    }

}