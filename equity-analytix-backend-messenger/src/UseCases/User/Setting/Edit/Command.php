<?php


namespace App\UseCases\User\Setting\Edit;


use App\UseCases\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Command
 *
 * @package   App\UseCases\User\Setting\Edit
 * @author    Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 */
class Command implements CommandInterface
{
    /**
     * @var int
     * @Assert\NotBlank()
     */
    public $userId;

    /**
     * @var int
     * @Assert\NotBlank()
     */
    public $settingId;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $signification;

    /**
     * Command constructor.
     *
     * @param int $userId
     * @param int $settingId
     */
    public function __construct(int $userId, int $settingId)
    {
        $this->userId = $userId;
        $this->settingId = $settingId;
    }
}
