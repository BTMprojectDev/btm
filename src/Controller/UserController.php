<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    private UserPasswordHasherInterface $passwordHasher;
    private UserRepository $userRepository;

    public function __construct
    (
        Security $security,
        ManagerRegistry $managerRegistry,
        UserPasswordHasherInterface $passwordHasher,
        UserRepository $userRepository
    )
    {
        $this->security = $security;
        $this->managerRegistry = $managerRegistry;
        $this->passwordHasher = $passwordHasher;
        $this->userRepository = $userRepository;
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
     * @Rest\Get("/all", name="get_all")
     * @Rest\View(serializerGroups={"get_user"})
     */
    public function getAllUserAction()
    {
        return $this->userRepository->findAll();
    }

    /**
     * @Rest\Delete("/{id}", name="delete_user_by_id")
     */
    public function deleteUserAction(int $id)
    {
        $user = $this->userRepository->findOneBy(['id' => $id]);
        $this->managerRegistry->getManager()->remove($user);
        return new JsonResponse(
            null,
            Response::HTTP_OK
        );
    }


    /**
     * @Rest\Post("", name="create")
     * @Rest\View()
     * @ParamConverter("user", converter="fos_rest.request_body")
     * @throws ORMException
     */
    public function postUserAction(User $user) : JsonResponse
    {
        $entityManager = $this->managerRegistry->getManager();
        $plainPass = $user->getPassword();
        $hashedPass = $this->passwordHasher->hashPassword(
            $user,
            $plainPass
        );
        $user->setPassword($hashedPass);
        $entityManager->persist($user);
        $entityManager->flush();


        return new JsonResponse(null,Response::HTTP_CREATED);
    }

}
