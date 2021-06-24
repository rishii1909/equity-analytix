<?php

namespace App\Entity\Chat\Setting;

use Webmozart\Assert\Assert;

/**
 * Class Name
 *
 * @package   App\Entity\Chat\Setting
 * @author    Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 */
class Name
{
    public const NUMBER_OF_FLASHES = 'numberOfFlashes';
    public const WEB_LINK_COLOR = 'webLinkColor';
    public const STOCK_SYMBOL_COLOR = 'stockSymbolColor';
    public const TEXT_SIZE = 'textSize';
    public const TEXT_BOLD = 'textBold';
    public const INCOMING_MESSAGES = 'incomingMessages';
    public const THEME = 'theme';
    public const LIGHT_THEME_SELECTED_FIRST_TIME = 'isLightThemeSelectedFirstTime';
    public const FLASHING_SPEED = 'flashingSpeed';
    public const DELIVERED_TIME_COLOR = 'deliveredTimeColor';
    public const HIDE_ICONS = 'hideIcons';

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
        Assert::oneOf(
            $name,
            [
                self::NUMBER_OF_FLASHES,
                self::WEB_LINK_COLOR,
                self::STOCK_SYMBOL_COLOR,
                self::TEXT_SIZE,
                self::TEXT_BOLD,
                self::INCOMING_MESSAGES,
                self::THEME,
                self::LIGHT_THEME_SELECTED_FIRST_TIME,
                self::FLASHING_SPEED,
                self::DELIVERED_TIME_COLOR,
                self::HIDE_ICONS,
            ]
        );

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
     * @param Name $name
     *
     * @return bool
     */
    public function isEqual(Name $name): bool
    {
        return $this->getName() === $name->getName();
    }
}
