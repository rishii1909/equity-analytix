<?php


namespace App\Entity\Chat\Setting;


use Webmozart\Assert\Assert;

class Signification
{

    /**
     * @var string
     */
    private $value;

    /**
     * Id constructor.
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        Assert::string($value);
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
     * @return string
     */
    public function __toString()
    {
        return $this->getValue();
    }

}