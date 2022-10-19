<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/api/user", name="api_user_")
 */

class UserController extends AbstractFOSRestController
{
    private Security $security;

    public function __construct
    (
        Security $security
    )
    {
        $this->security = $security;
    }

    /**
     * @Rest\Get("", name="get")
     *
     */
    public function getUserAction()
    {
        return $this->security->getUser();
    }
}
