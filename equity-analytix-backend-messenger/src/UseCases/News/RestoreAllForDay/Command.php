<?php

namespace App\UseCases\News\RestoreAllForDay;

use App\UseCases\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Command implements CommandInterface
{
    /**
     * @Assert\Positive()
     */
    public $id;

    /**
     * Command constructor.
     *
     * @param int    $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }
}
