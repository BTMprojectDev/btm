<?php

namespace App\Controller;

use App\Entity\User;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Json;

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
     * @Rest\View(serializerGroups={"get_user"})
     */
    public function getUserAction()
    {
        return $this->security->getUser();
    }

    /**
     * @Rest\Post("", name="create")
     * @Rest\View()
     * @ParamConverter("user", converter="fos_rest.request_body")
     */
    public function postUserAction(User $user) : JsonResponse
    {

        dd($user);
        return new JsonResponse(null,Response::HTTP_CREATED);
    }

}
