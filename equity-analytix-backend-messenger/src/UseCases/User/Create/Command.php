<?php

declare(strict_types=1);


namespace App\UseCases\User\Create;

use App\UseCases\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Command
 *
 * @package   App\Entity\Chat\UseCases\User\Create
 * @author    Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 */
class Command implements CommandInterface
{
    /**
     * @Assert\NotBlank()
     */
    public $userId;

    /**
     * @Assert\NotBlank()
     */
    public $userName;

    /**
     * @Assert\NotBlank()
     */
    public $role;
}