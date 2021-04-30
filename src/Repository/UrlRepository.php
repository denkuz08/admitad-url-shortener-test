<?php

namespace App\Repository;

use App\Entity\Url;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Url|null find($id, $lockMode = null, $lockVersion = null)
 * @method Url|null findOneBy(array $criteria, array $orderBy = null)
 * @method Url[]    findAll()
 * @method Url[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UrlRepository extends ServiceEntityRepository
{
    public const GROUP_BY_DATE = 1 << 0;
    public const GROUP_BY_USER = 1 << 1;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Url::class);
    }

    public function getCountUrlsStat(
        \DateTimeInterface $from,
        \DateTimeInterface $to,
        User $user = null,
        int $groupByMask = self::GROUP_BY_USER
    ): iterable {
        $qb = $this->createQueryBuilder('url');
        $qb
            ->select($qb->expr()->count('url.id') . 'AS count_urls')
            ->where($qb->expr()->between('url.createdAt', ':from', ':to'));

        $qb->setParameters([':from' => $from, ':to' => $to]);

        if ($groupByMask & self::GROUP_BY_USER) {
            $qb->addSelect('user.id AS user_id');
            $qb->addSelect('user.login AS user_login');
            $qb->addGroupBy('url.createdUser');
            $qb->leftJoin('url.createdUser', 'user');
        }

        if ($groupByMask & self::GROUP_BY_DATE) {
            $qb->addSelect('url.createdAt AS date');
            $qb->addGroupBy('url.createdAt');
        }

        if ($user) {
            $qb->andWhere('url.createdUser = :user');
            $qb->setParameter(':user', $user);
        }

        return $qb->getQuery()->getScalarResult();
    }
}
