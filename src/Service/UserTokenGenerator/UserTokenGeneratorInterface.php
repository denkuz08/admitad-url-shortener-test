<?php

namespace App\Service\UserTokenGenerator;

use Symfony\Component\Security\Core\User\UserInterface;

interface UserTokenGeneratorInterface
{
    public function generateTokenForUser(UserInterface $user): string;
}
