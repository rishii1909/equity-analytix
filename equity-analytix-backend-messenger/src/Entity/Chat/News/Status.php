<?php


namespace App\Entity\Chat\News;


use Webmozart\Assert\Assert;

/**
 * Class Status
 *
 * @package   App\Entity\Chat\News
 * @author    Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 */
class Status
{

    public const NONE = 'none';
    public const BEAR = 'bear';
    public const BULL = 'bull';
    public const ARROW_NEG = 'arrowNeg';
    public const ARROW_POS = 'arrowPos';
    public const EYE_NEG = 'eyeNeg';
    public const EYE_POS = 'eyePos';
    public const SIGHT_NEG = 'sightNeg';
    public const SIGHT_POS = 'sightPos';
    public const XFILES_NEG = 'xfilesNeg';
    public const XFILE_POS = 'xfilesPos';
    public const SWITCH = 'switch';
    public const ALERT = 'alert';


    /**
     * @var string
     */
    private $name;

    /**
     * Status constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        Assert::oneOf($name,[
            self::NONE,
            self::BEAR,
            self::BULL,
            self::ARROW_NEG,
            self::ARROW_POS,
            self::EYE_NEG,
            self::EYE_POS,
            self::SIGHT_NEG,
            self::SIGHT_POS,
            self::XFILES_NEG,
            self::XFILE_POS,
            self::ALERT,
            self::SWITCH,
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
     * @return self
     */
    public static function none(): self
    {
        return new self(self::NONE);
    }

    /**
     * @return self
     */
    public static function bear(): self
    {
        return new self(self::BEAR);
    }

    /**
     * @return self
     */
    public static function bull(): self
    {
        return new self(self::BULL);
    }

}