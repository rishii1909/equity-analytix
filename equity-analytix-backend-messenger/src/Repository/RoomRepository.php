<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Chat\Room\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Room|null find($id, $lockMode = null, $lockVersion = null)
 * @method Room|null findOneBy(array $criteria, array $orderBy = null)
 * @method Room[]    findAll()
 * @method Room[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomRepository extends ServiceEntityRepository
{
	/**
	 * @param ManagerRegistry $registry
	 */
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Room::class);
	}

	/**
	 * @param string $roomToken
	 * @return Room|null
	 */
	public function findRoomByToken(string $roomToken):?Room
	{
		try {
			return $this->createQueryBuilder('r')
				->where('r.token = :token')
				->setParameter('token', $roomToken)
				->getQuery()
				->getOneOrNullResult();
		} catch (NonUniqueResultException $e) {
			return null;
		}
	}

	/**
	 * @param integer $userId
	 * @param string  $type
	 * @return boolean
	 */
	public function hasPrivateChat(int $userId, $type = 'private'): bool
	{
		try {
			return $this->createQueryBuilder('r')
					->select('COUNT(r.id)')
					->join('r.participants', 'p')
					->where('p.id = :firstUser')
					->andWhere('r.type = :type')
					->setParameter(':firstUser', $userId)
					->setParameter(':type', $type)
					->getQuery()
					->getSingleScalarResult() > 0;
		} catch (NoResultException|NonUniqueResultException $e) {
			return false;
		}
	}

	/**
	 * @param integer $userAdmin
	 * @param integer $userWeb
	 * @param string  $type
	 * @return Room
	 */
	public function findPrivateRoomForUser(int $userAdmin, int $userWeb, $type = 'private'): Room
	{
		try {
			return $this->createQueryBuilder('r')
					->join('r.participants', 'p1')
					->join('r.participants', 'p2')
					->where('p1.id = :userAdmin')
					->andWhere('p2.id = :userWeb')
					->andWhere('r.type = :type')
					->setParameters([
						'userAdmin' => $userAdmin,
						'userWeb'   => $userWeb,
						'type'       => $type,
					])
					->getQuery()
					->getOneOrNullResult();
		} catch (NonUniqueResultException $e) {
			return null;
		}
	}
}