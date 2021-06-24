<?php

declare(strict_types=1);


namespace App\Entity\Chat\Message;


use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

/**
 * Class Id
 *
 * @package App\Entity\Chat\Message
 */
class Id
{
    /**
     * @var string
     */
    private $value;

    /**
     * Id constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        $this->value = $value;
    }


    /**
     * @return self
     */
    public static function next(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getValue();
    }

}