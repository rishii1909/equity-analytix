<?php

declare(strict_types=1);

namespace App\Entity\Files\File;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use DomainException;

/**
 * Class File
 *
 * @package App\Entity\Files\File
 *
 * @ORM\Entity()
 * @ORM\Table(name="file_files", indexes={
 *     @ORM\Index(columns={"date"}),
 *     @ORM\Index(columns={"entity_type", "entity_id"})
 * })
 */
class File
{
    /**
     * @var Id
     * @ORM\Column(type="file_files_id")
     * @ORM\Id()
     */
    private $id;
    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $date;
    /**
     * @var Info
     * @ORM\Embedded(class="Info")
     */
    private $info;
    /**
     * @var Entity
     * @ORM\Embedded(class="Entity")
     */
    private $entity;

    /**
     * File constructor.
     *
     * @param Id                $id
     * @param DateTimeImmutable $date
     * @param Info              $info
     */
    public function __construct(Id $id, DateTimeImmutable $date, Info $info)
    {
        $this->id = $id;
        $this->date = $date;
        $this->info = $info;
    }

    /**
     * @param Entity $entity
     */
    public function addEntity(Entity $entity): void
    {
        if ($this->entity->isFilled()) {
            throw new DomainException('File ' . $this->info->getFullPath() . ' is already attached.');
        }

        $this->entity = $entity;
    }

    /**
     * @return Id
     */
    public function getId(): Id
    {
        return $this->id;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @return Info
     */
    public function getInfo(): Info
    {
        return $this->info;
    }

    /**
     * @return Entity
     */
    public function getEntity(): Entity
    {
        return $this->entity;
    }
}
