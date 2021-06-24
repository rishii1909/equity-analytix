<?php

declare(strict_types=1);

namespace App\UseCases\File\Entity\Add;

/**
 * Class Command
 *
 * @package App\UseCases\File\Entity\Add
 */
class Command
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $entityId;
    /**
     * @var string
     */
    public $entityType;

    /**
     * Command constructor.
     *
     * @param string $id
     * @param string $entityId
     * @param string $entityType
     */
    public function __construct(string $id, string $entityId, string $entityType)
    {
        $this->id = $id;
        $this->entityId = $entityId;
        $this->entityType = $entityType;
    }
}
