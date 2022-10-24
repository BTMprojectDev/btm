<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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

    public function __construct
    (
        Security $security,
        ManagerRegistry $managerRegistry,
        UserRepository $userRepository,
        UserPasswordHasherInterface $userPasswordHasher
    )
    {
        $this->security = $security;
        $this->managerRegistry = $managerRegistry;
        $this->userRepository = $userRepository;
        $this->userPasswordHasher = $userPasswordHasher;
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
     * @Rest\Delete("/{id}", name="delete_user_by_id")
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     */
    public function delete(int $id)
    {
        $user = $this->userRepository->findOneBy(['id' => $id]);
        $this->managerRegistry->getManager()->remove($user);
        return new JsonResponse(
            null,
            Response::HTTP_NO_CONTENT
        );
    }

    /**
     * @Rest\Put("/{id}", name="put_by_user_id")
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     */
    public function put(int $id, Request $request)
    {
        $oldUser = $this->userRepository->find($id);
        $newUser = $request->request->all();

        dd();

    }

    /**
     * @Rest\Patch("/{id}", name="patch_by_user_id")
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     */
    public function patch(int $id, Request $request)
    {
        $oldUser = $this->userRepository->find($id);
        $newUser = $request->request->all();
        if($newUser['email']){
            $oldUser->setEmail($newUser['email']);
        }
        if($newUser['password']){
            $plainPassword = $newUser['password'];
            $hashedPassword = $this->userPasswordHasher->hashPassword(
                $oldUser,
                $plainPassword
            );
            $oldUser->setPassword($hashedPassword);
        }
        if($newUser['lat']){
            $oldUser->setLat($newUser['lat']);
        }
        if($newUser['lng']){
            $oldUser->setLng($newUser['lng']);
        }
        if($newUser['photoUrl']){
            $oldUser->setPhotoUrl($newUser['photoUrl']);
        }
        if($newUser['firstName']){
            $oldUser->setFirstName($newUser['firstName']);
        }
        if($newUser['lastName']){
            $oldUser->setLastName($newUser['lastName']);
        }
        if($newUser['dateOfBirth']){
            $oldUser->setDateOfBirth($newUser['dateOfBirth']);
        }
        $this->managerRegistry->getManager()->flush();

        return $oldUser;

    }
}
