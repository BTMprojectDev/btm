<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/task", name="api_task_")
 */

class TaskController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/{id}", name="get_by_id")
     * @Rest\View(serializerGroups={"task"})
     */
    public function getTaskAction(Task $task)
    {
        return $task;
    }
}

