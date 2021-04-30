<?php

namespace App\Service\UrlStatService;

use App\Entity\User;
use App\Repository\UrlRepository;
use App\Service\UrlStatService\Exception\UnknownGroupByTypeException;

class UrlStatService
{
    public const DEFAULT_START_DATE = '-1 week';
    public const DEFAULT_END_DATE = 'today';

    public const GROUP_BY_USER = 'user';
    public const GROUP_BY_DATE = 'date';
    public const GROUP_BY_USER_DATE = 'user_date';

    public const POSSIBLE_GROUP_BY = [
        self::GROUP_BY_DATE,
        self::GROUP_BY_USER,
        self::GROUP_BY_USER_DATE,
    ];

    private UrlRepository $urlRepository;

    public function __construct(UrlRepository $urlRepository)
    {
        $this->urlRepository = $urlRepository;
    }

    public function getStat(
        \DateTimeInterface $from = null,
        \DateTimeInterface $to = null,
        ?User $user = null,
        ?string $groupBy = self::GROUP_BY_USER
    ): iterable {
        if (!$from) {
            $from = new \DateTimeImmutable(self::DEFAULT_START_DATE);
        }

        if (!$to) {
            $to = new \DateTimeImmutable(self::DEFAULT_END_DATE);
        }

        if ($from > $to) {
            [$from, $to] = [$to, $from];
        }

        return $this->urlRepository->getCountUrlsStat($from, $to, $user, $this->buildGroupByMask($groupBy));
    }

    protected function buildGroupByMask(string $groupBy): int
    {
        switch ($groupBy) {
            case self::GROUP_BY_USER_DATE:
                return UrlRepository::GROUP_BY_DATE | UrlRepository::GROUP_BY_USER;

            case self::GROUP_BY_DATE:
                return UrlRepository::GROUP_BY_DATE;

            case self::GROUP_BY_USER:
                return UrlRepository::GROUP_BY_USER;
        }

        $ex = new UnknownGroupByTypeException(sprintf('Unknown group by type: %s'.$groupBy));
        $ex->setType($groupBy);

        throw $ex;
    }
}
