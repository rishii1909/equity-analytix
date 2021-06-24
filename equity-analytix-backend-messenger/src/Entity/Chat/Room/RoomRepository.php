<?php
declare(strict_types = 1);

namespace App\Entity\Chat\Room;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;

/**
 * Class RoomRepository
 */
class RoomRepository
{
    /**
     * @var EntityRepository
     */
    private $repo;

    /**
     * RoomRepository constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(Room::class);
    }

    /**
     * @param $id
     * @return Room
     * @throws EntityNotFoundException
     */
    public function get($id): Room
    {
        /** @var Room $room */
        if (!$room = $this->repo->find($id)) {
            throw new EntityNotFoundException('Room not found');
        }

        return $room;
    }

    /**
     * @param $user
     * @param string $type
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @return bool
     */
    public function hasPrivatChat($user, $type = 'private'): bool
    {
       return $this->repo->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->join('r.participants', 'p')
            ->where('p.id = :firstUser')
            ->andWhere('r.type = :type')
            ->setParameter(':firstUser', $user)
            ->setParameter(':type', $type)
            ->getQuery()->getSingleScalarResult() > 0;
    }
}