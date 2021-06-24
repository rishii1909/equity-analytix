<?php

declare(strict_types=1);


namespace App\Entity\Chat\User;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * Class UserRepository
 *
 * @package   App\Entity\Chat\User
 * @author    Polvanov Igor <igor.polvanov@sibers.com>
 * @copyright 2020 (c) Sibers
 */
class UserRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var EntityRepository
     */
    private $repo;

    /**
     * UserRepository constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(User::class);
        $this->em = $em;
    }

    /**
     * @param $id
     *
     * @return User
     */
    public function get($id): User
    {
        /** @var User $user */
        if (!$user = $this->repo->find($id)) {
            throw new \DomainException('User not found');
        }

        return $user;
    }

    /**
     * @param $id
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @return bool
     */
    public function has($id): bool
    {
        return $this->repo->createQueryBuilder('u')
                ->select('COUNT(u.id)')
                ->andWhere('u.id = :id')
                ->setParameter(':id', $id)
                ->getQuery()->getSingleScalarResult() > 0;
    }

    /**
     * @param User $user
     */
    public function add(User $user)
    {
        $this->em->persist($user);
    }
}