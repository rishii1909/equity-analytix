<?php

declare(strict_types=1);

namespace App\UseCases;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Class Flusher
 */
class Flusher
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * Flusher constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function flush(): void
    {
        $this->em->flush();
    }
}