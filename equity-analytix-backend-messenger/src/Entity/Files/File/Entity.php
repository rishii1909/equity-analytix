<?php

declare(strict_types=1);

namespace App\Entity\Files\File;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * @ORM\Embeddable()
 */
class Entity
{
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $type;
    /**
     * @var string
     * @ORM\Column(type="string", length=36, nullable=true)
     */
    private $id;

    /**
     * Entity constructor.
     *
     * @param string $type
     * @param string $id
     */
    public function __construct(string $type, string $id)
    {
        Assert::notEmpty($type);
        Assert::notEmpty($id);

        $this->type = $type;
        $this->id = $id;
    }

    public function isFilled(): bool
    {
        return $this->type !== null && $this->id !== null;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}
