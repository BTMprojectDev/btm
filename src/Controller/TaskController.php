<?php

namespace App\Controller;

use App\Entity\Task;
use Doctrine\Persistence\ManagerRegistry;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @Route("/api/task", name="api_task_")
 */

class TaskController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/{id}", name="get")
     * @Rest\View(serializerGroups={"task"})
     */
    public function getTaskAction(Task $task)
    {
        return $task;
    }
}
