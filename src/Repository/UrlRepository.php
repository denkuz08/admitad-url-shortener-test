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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Url::class);
    }

    public function getCountUrlsGroupByUser(\DateTimeInterface $from, \DateTimeInterface $to, User $user = null): iterable
    {
        $qb = $this->createQueryBuilder('url');

        $qb
            ->select('user.id AS user_id')
            ->addSelect('user.login AS user_login')
            ->addSelect($qb->expr()->count('url.id') . 'AS count_urls')
            ->andWhere($qb->expr()->between('url.createdAt', ':from', ':to'))
            ->leftJoin('url.createdUser', 'user')
            ->groupBy('user.id');

        $qb->setParameters([':from' => $from, ':to' => $to]);

        if ($user) {
            $qb->andWhere('url.createdUser = :user');
            $qb->setParameter(':user', $user);
        }

        return $qb->getQuery()->getScalarResult();
    }

    public function getCountUrlsGroupByDate(\DateTimeInterface $from, \DateTimeInterface $to, User $user = null): iterable
    {
        $qb = $this->createQueryBuilder('url');

        $qb
            ->select('url.createdAt AS date')
            ->addSelect($qb->expr()->count('url.id') . 'AS count_urls')
            ->andWhere($qb->expr()->between('url.createdAt', ':from', ':to'))
            ->groupBy('url.createdAt');

        $qb->setParameters([':from' => $from, ':to' => $to]);

        if ($user) {
            $qb->andWhere('url.createdUser = :user');
            $qb->setParameter(':user', $user);
        }

        return $qb->getQuery()->getScalarResult();
    }
}
