<?php


namespace App\Entity\Chat\User;


use Webmozart\Assert\Assert;

/**
 * Class Role
 *
 * @package   App\Entity\Chat\User
 * @author    Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 */
class Role
{

    public const USER = 'user';
    public const ADMIN = 'administrator';
    public const SUBSCRIBER = 'subscriber';

    /**
     * @var string
     */
    private $value;


    /**
     * Role constructor.
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        Assert::oneOf($value,[
            self::USER,
            self::ADMIN,
            self::SUBSCRIBER,
        ]);

        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param Role $other
     *
     * @return bool
     */
    public function isEqual(self $other): bool
    {
        return $this->getValue() === $other->getValue();
    }

    /**
     * @return self
     */
    public static function user(): self
    {
        return new self(self::USER);
    }

    /**
     * @return self
     */
    public static function admin(): self
    {
        return new self(self::ADMIN);
    }

    /**
     * @return self
     */
    public static function subscriber(): self
    {
        return new self(self::SUBSCRIBER);
    }

    /**
     * @return bool
     */
    public function isAdmin():bool
    {
        return $this->getValue() === self::ADMIN;
    }

}