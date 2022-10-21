<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/task", name="api_task")
 */

class TaskController extends AbstractFOSRestController
{
    private TaskRepository $taskRepository;

    public function __construct
    (
        TaskRepository $taskRepository,
    )
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @Rest\Get("/{task}", name="get_by_id")
     * @Rest\View(serializerGroups={"task"})
     */
    public function show(Task $task)
    {
        return $task;
    }

    /**
     * @Rest\Get("", name="get_all_task")
     * @Rest\View(serializerGroups={"task"})
     */
    public function all()
    {
        return $this->taskRepository->findAll();
    }
}

