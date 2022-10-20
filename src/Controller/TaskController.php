<?php

namespace App\Controller;

use App\Entity\Task;
use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/api/task", name="api_task_")
 */

class TaskController extends AbstractFOSRestController
{
    //private $entityManager;
    private $security;

    public function __construct(
        //EntityManager $entityManager,
        Security      $security
    )
    {
        //$this->entityManager = $entityManager;
        $this->security = $security;
    }

    /**
     * @Rest\Get("/{userid}", name="get")
     */
    public function getTaskAction(int $userid)
    {
        $temp = 'd';
        return $userid;
    }
}
