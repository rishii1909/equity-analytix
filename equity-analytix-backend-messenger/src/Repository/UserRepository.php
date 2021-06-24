<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Chat\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
	/**
	 * @param ManagerRegistry $registry
	 */
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, User::class);
	}

    /**
     * @param integer $userId
     * @return User|null
     */
	public function findById(int $userId): ?User
    {
        try {
            return $this->createQueryBuilder('user')
                ->where('user.id = :userId')
                ->setParameter('userId', $userId)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }
}