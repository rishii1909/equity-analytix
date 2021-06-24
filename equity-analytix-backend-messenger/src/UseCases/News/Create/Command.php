<?php

declare(strict_types=1);


namespace App\UseCases\News\Create;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Command
 *
 * @package   App\Entity\Chat\UseCases\News\Create
 * @author    Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 */
class Command
{
    /**
     * @Assert\NotBlank()
     */
    public $user;

    /**
     * @Assert\NotBlank()
     */
    public $text;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $status;
    /**
     * @var string[]
     */
    public $attachments = [];
}
