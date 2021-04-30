<?php

namespace App\EventListener;

use App\Entity\User;
use App\Service\UserTokenGenerator\UserTokenGeneratorInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class UserCreationListener implements EventSubscriber
{
    private UserTokenGeneratorInterface $tokenGenerator;

    public function __construct(UserTokenGeneratorInterface $tokenGenerator)
    {
        $this->tokenGenerator = $tokenGenerator;
    }

    public function getSubscribedEvents()
    {
        return [Events::prePersist];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $user = $args->getObject();

        if (!$user instanceof User) {
            return;
        }

        if ($user->getApiToken() !== null) {
            return;
        }

        $user->setApiToken($this->tokenGenerator->generateTokenForUser($user));
    }
}
