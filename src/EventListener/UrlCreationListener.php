<?php

namespace App\EventListener;

use App\Entity\Url;
use App\Entity\User;
use App\Service\UrlShortener\UrlShortenerInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UrlCreationListener implements EventSubscriber
{
    private UrlShortenerInterface $urlShortener;

    private TokenStorageInterface $tokenStorage;

    public function __construct(UrlShortenerInterface $urlShortener, TokenStorageInterface $tokenStorage)
    {
        $this->urlShortener = $urlShortener;
        $this->tokenStorage = $tokenStorage;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::postPersist,
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $url = $args->getObject();
        if (!$url instanceof Url) {
            return;
        }

        if (!$url->getCreatedUser()) {
            $token = $this->tokenStorage->getToken();
            if ($token && $token->getUser() instanceof User) {
                $url->setCreatedUser($token->getUser());
            }
        }

        if (!$url->getCreatedAt()) {
            $url->setCreatedAt(new \DateTime());
        }
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $url = $args->getObject();
        if (!$url instanceof Url) {
            return;
        }

        if ($url->getShortCode() !== null) {
            return;
        }

        $url->setShortCode($this->urlShortener->generateShortCode($url));
        $args->getObjectManager()->flush();
    }
}
