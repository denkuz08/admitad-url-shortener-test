<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UrlStatService\UrlStatService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Rest\Route("/api/stats")
 */
class StatsController extends AbstractFOSRestController
{
    private UserRepository $userRepository;

    private UrlStatService $urlStatService;

    public function __construct(UserRepository $userRepository, UrlStatService $urlStatService)
    {
        $this->userRepository = $userRepository;
        $this->urlStatService = $urlStatService;
    }

    /**
     * @Rest\Get("/", name="api_stats")
     * @Rest\QueryParam(
     *     name="user_id",
     *     nullable=true,
     *     requirements={@Assert\Positive()}
     * )
     * @Rest\QueryParam(
     *     name="group_by",
     *     nullable=true,
     *     default=App\Service\UrlStatService\UrlStatService::GROUP_BY_USER,
     *     requirements={
     *         @Assert\Choice(
     *             App\Service\UrlStatService\UrlStatService::POSSIBLE_GROUP_BY
     *         )
     *     }
     * )
     * @Rest\View()
     */
    public function stats(ParamFetcher $paramFetcher)
    {
        $paramFetcher->addParam($this->createDateQueryParam('date_from'));
        $paramFetcher->addParam($this->createDateQueryParam('date_to'));

        $userIdFilter = $paramFetcher->get('user_id', true);
        $dateFromFilter = $paramFetcher->get('date_from', true);
        $dateToFilter = $paramFetcher->get('date_to', true);
        $groupByFilter = $paramFetcher->get('group_by', true);

        $dateFrom = $dateFromFilter ? new \DateTimeImmutable($dateFromFilter) : null;
        $dateTo = $dateToFilter ? new \DateTimeImmutable($dateToFilter) : null;
        $user = $userIdFilter ? $this->getUserById($userIdFilter) : null;

        $dateTo ??= $dateFrom;
        $dateFrom ??= $dateTo;

        return $this->urlStatService->getStat($dateFrom, $dateTo, $user, $groupByFilter);
    }

    private function createDateQueryParam(string $name): Rest\QueryParam
    {
        $dateParam = new Rest\QueryParam();
        $dateParam->name = $name;
        $dateParam->nullable = true;
        $dateParam->requirements = new Assert\Date();

        return $dateParam;
    }

    private function getUserById(string $id): ?User
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }

        return $user;
    }
}
