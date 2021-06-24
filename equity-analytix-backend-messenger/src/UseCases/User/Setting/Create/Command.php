<?php

namespace App\UseCases\User\Setting\Create;

use App\UseCases\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Command
 *
 * @package   App\UseCases\User\Setting\Create
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
    public $name;

    /**
     * @Assert\NotBlank()
     */
    public $signification;

    /**
     * Command constructor.
     *
     * @param int $userId
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }
}