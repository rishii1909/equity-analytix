<?php

declare(strict_types=1);

namespace App\UseCases\Message\View;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Command
 *
 * @package App\UseCases\Message\View
 */
class Command
{
    /**
     * @var string
     * @Assert\NotNull()
     */
    public $id;

    /**
     * @var int
     * @Assert\NotNull()
     */
    public $userId;

    /**
     * @var int
     * @Assert\NotNull()
     */
    public $roomId;

    /**
     * Command constructor.
     *
     * @param int $userId
     * @param int $roomId
     */
    public function __construct(int $userId, int $roomId)
    {
        $this->userId = $userId;
        $this->roomId = $roomId;
    }
}
