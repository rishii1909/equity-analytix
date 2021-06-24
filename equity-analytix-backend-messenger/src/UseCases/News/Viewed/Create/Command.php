<?php


namespace App\UseCases\News\Viewed\Create;

use App\UseCases\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Command
 *
 * @package   App\UseCases\News\Viewed
 * @author    Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 */
class Command implements CommandInterface
{
    /**
     * @Assert\NotBlank()
     */
    public $id;

    /**
     * @Assert\NotBlank()
     */
    public $userId;

    /**
     * Command constructor.
     *
     * @param string $id
     * @param int $userId
     */
    public function __construct(string $id, int $userId)
    {
        $this->id = $id;
        $this->userId = $userId;
    }

}