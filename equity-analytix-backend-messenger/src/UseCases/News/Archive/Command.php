<?php

namespace App\UseCases\News\Archive;

use Symfony\Component\Validator\Constraints as Assert;

class Command
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