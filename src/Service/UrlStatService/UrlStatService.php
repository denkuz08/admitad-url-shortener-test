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
    public const POSSIBLE_GROUP_BY = [
        self::GROUP_BY_DATE,
        self::GROUP_BY_USER,
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
        if (!\in_array($groupBy, self::POSSIBLE_GROUP_BY)) {
            $groupBy = self::GROUP_BY_USER;
        }

        if (!$from) {
            $from = new \DateTimeImmutable(self::DEFAULT_START_DATE);
        }

        if (!$to) {
            $to = new \DateTimeImmutable(self::DEFAULT_END_DATE);
        }

        if ($from > $to) {
            [$from, $to] = [$to, $from];
        }

        switch ($groupBy) {
            case self::GROUP_BY_USER:
                return $this->urlRepository->getCountUrlsGroupByUser($from, $to, $user);
            case self::GROUP_BY_DATE:
                return $this->urlRepository->getCountUrlsGroupByDate($from, $to, $user);
        }

        $ex = new UnknownGroupByTypeException(sprintf('Unknown group by type: %s'.$groupBy));
        $ex->setType($groupBy);

        throw $ex;
    }
}
