<?php

declare(strict_types=1);


namespace App\Entity\Chat\News;


use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

/**
 * Class ArchiveType
 *
 * @package   App\Entity\Chat\News
 * @author    Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 */
class ArchiveType extends StringType
{

    public const NAME = 'chat_news_archive';

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     *
     * @return mixed|string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof Archive ? $value->getName() : $value;
    }


    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     * @return Archive|mixed|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new Archive($value) : null;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * @param AbstractPlatform $platform
     * @return bool
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }

}