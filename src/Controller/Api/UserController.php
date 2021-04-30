<?php

declare(strict_types=1);

namespace App\Controller\Api;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * @Rest\Route("/api/user")
 */
class UserController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/me.{_format}", name="api_user_me", defaults={"_format"="json"})
     * @Rest\View(serializerGroups={"api_me"})
     */
    public function me()
    {
        return $this->getUser();
    }
}
