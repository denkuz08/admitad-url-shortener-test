<?php

namespace App\Service\UserTokenGenerator;

use Symfony\Component\Security\Core\User\UserInterface;

class SimpleUserTokenGeneratorInterfaceService implements UserTokenGeneratorInterface
{
    public function generateTokenForUser(UserInterface $user): string
    {
        return md5(microtime() . $user->getUsername());
    }
}
