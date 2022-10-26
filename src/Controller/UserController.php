<?php

namespace App\Controller;


use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/api/user", name="api_user_")
 */

class UserController extends AbstractFOSRestController
{
    private Security $security;
    private ManagerRegistry $managerRegistry;
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $userPasswordHasher;
    private ParamFetcherInterface $paramFetcher;

    public function __construct
    (
        Security $security,
        ManagerRegistry $managerRegistry,
        UserRepository $userRepository,
        UserPasswordHasherInterface $userPasswordHasher,
        ParamFetcherInterface $paramFetcher
    )
    {
        $this->security = $security;
        $this->managerRegistry = $managerRegistry;
        $this->userRepository = $userRepository;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->paramFetcher = $paramFetcher;
    }

    /**
     * @Rest\Get("", name="get")
     * @Rest\View(serializerGroups={"get_user"}, statusCode=Response::HTTP_OK)
     */
    public function user()
    {
        return $this->security->getUser();
    }

    /**
     * @Rest\Get("/all", name="get_all")
     * @Rest\View(serializerGroups={"get_user"}, statusCode=Response::HTTP_OK)
     */
    public function users()
    {
        return $this->userRepository->findAll();
    }

    /**
     * @Rest\Delete("", name="delete_user_by_id")
     * @QueryParam(name="user_id", requirements="\d+", description="user_id")
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     */
    public function delete()
    {
        $id = $this->paramFetcher->get("user_id");
        $user = $this->userRepository->findOneBy(['id' => $id]);
        $this->managerRegistry->getManager()->remove($user);
        $this->managerRegistry->getManager()->flush();
        return new JsonResponse(
            null,
            Response::HTTP_NO_CONTENT
        );
    }

    /**
     * @Rest\Put("", name="put_by_user_id")
     * @QueryParam(name="user_id", requirements="\d+", description="user_id")
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     */
    public function put(Request $request)
    {
        $id = $this->paramFetcher->get("user_id");
        $existingUser = $this->userRepository->find($id);
        $newUser = $request->request->all();
        $form = $this->createForm(UserType::class, $existingUser);

        $plainPassword = $newUser['password'];
        $hashedPassword = $this->userPasswordHasher->hashPassword(
            $existingUser,
            $plainPassword
        );
        $newUser["password"] = $hashedPassword;

        $form->submit($newUser, false);
        if(false === $form->isValid()){
            return new JsonResponse(
                null,
                Response::HTTP_BAD_REQUEST
            );
        }

        $this->managerRegistry->getManager()->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);

    }

    /**
     * @Rest\Patch("", name="patch_by_user_id")
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @QueryParam(name="user_id", requirements="\d+", description="user_id")
     * @throws Exception
     */
    public function patch(Request $request)
    {
        $id = $this->paramFetcher->get("user_id");
        $existingUser = $this->userRepository->find($id);
        $newUser = $request->request->all();
        $form = $this->createForm(UserType::class, $existingUser);
        $form->submit($newUser, false);
        if(false === $form->isValid()){
            return new JsonResponse(
                null,
                Response::HTTP_BAD_REQUEST
            );
        }
        $this->managerRegistry->getManager()->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
