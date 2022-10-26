<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/task", name="api_task_")
 */

class TaskController extends AbstractFOSRestController
{
    private TaskRepository $taskRepository;

    public function __construct(
        TaskRepository $taskRepository
    )
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @Rest\Get("/{id}", name="get_by_id")
     * @Rest\View(serializerGroups={"task"})
     */
    public function show(Task $task)
    {
        return $task;
    }

    /**
     * @Rest\Get("", name="get_all")
     * @Rest\View(serializerGroups={"task"})
     */
    public function all()
    {
        return $this->taskRepository->findAll();
    }
}

