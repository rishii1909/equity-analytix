<?php

declare(strict_types=1);


namespace App\Entity\Chat\News;


use Webmozart\Assert\Assert;

/**
 * Class Status
 *
 * @package App\Entity\Chat\News
 */
class Archive
{
    public const ACTIVE = 'active';
    public const ARCHIVED = 'archived';

    private $name;

    /**
     * Archive constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        Assert::oneOf($name,[
            self::ACTIVE,
            self::ARCHIVED,
        ]);

        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param Archive $other
     * @return bool
     */
    public function isEqual(self $other): bool
    {
        return $this->getName() === $other->getName();
    }

    /**
     * @return static
     */
    public static function active(): self
    {
        return new self(self::ACTIVE);
    }

    /**
     * @return static
     */
    public static function archived(): self
    {
        return new self(self::ARCHIVED);
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->name === self::ACTIVE;
    }

    /**
     * @return bool
     */
    public function isArchived(): bool
    {
        return $this->name === self::ARCHIVED;
    }

}