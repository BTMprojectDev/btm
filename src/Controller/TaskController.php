<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Response;

/**
 * @Route("/api/task", name="api_task")
 */

class TaskController extends AbstractFOSRestController
{
    private TaskRepository $taskRepository;
    private EntityManagerInterface $entityManager;

    public function __construct
    (
        EntityManagerInterface $entityManager,
        TaskRepository $taskRepository
    )
    {
        $this->taskRepository = $taskRepository;
        $this->entityManager = $entityManager;
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

    /**
     * @Rest\Post("", name="post_new_task")
     * @ParamConverter("task", converter="fos_rest.request_body")
     * @Rest\View(statusCode= 200)
     */
    public function add(Task $task) : Task
    {
        $this->entityManager->persist($task);
        $this->entityManager->flush();
        return $task;
    }
}

