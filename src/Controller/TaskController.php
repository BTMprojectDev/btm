<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/task", name="api_task")
 */

class TaskController extends AbstractFOSRestController
{
    private TaskRepository $taskRepository;
    private EntityManagerInterface $entityManager;
    private ParamFetcherInterface $paramFetcher;

    public function __construct
    (
        EntityManagerInterface $entityManager,
        TaskRepository $taskRepository,
        ParamFetcherInterface $paramFetcher
    )
    {
        $this->taskRepository = $taskRepository;
        $this->entityManager = $entityManager;
        $this->paramFetcher = $paramFetcher;
    }

    /**
     * @Rest\Get("/{id}", name="get_task_by_id")
     * @Rest\View(serializerGroups={"task"})
     */
    public function show(Task $task)
    {
        return $task;
    }

    /**
     * @Rest\Get("", name="get_all_task")
     * @QueryParam(name="user_id", requirements="\d+", default="0", description="User id")
     * @Rest\View(serializerGroups={"task"}, statusCode=Response::HTTP_FOUND)
     */
    public function all()
    {
        $user_id = $this->paramFetcher->get('user_id');
        return $this->taskRepository->findBy(["user"=>$user_id]);
    }

    /**
     * @Rest\Post("", name="post_task")
     * @ParamConverter("task", converter="fos_rest.request_body")
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     */
    public function add(Task $task) : Task
    {
        $this->entityManager->persist($task);
        $this->entityManager->flush();
        return $task;
    }

    /**
     * @Rest\Delete("/{id}", name="del_by_id")
     * @Rest\View(statusCode=Response::HTTP_OK)
     */
    public function del(Task $task)
    {
        $this->entityManager->remove($task);
        $this->entityManager->flush();
    }

    /**
     * @Rest\Patch("/{id}", name="update_task_by_id")
     * @ParamConverter("task", converter="fos_rest.request_body")
     * @Rest\View(statusCode=Response::HTTP_OK)
     */
    public function update(int $id, Task $task)
    {
        $oldTask = $this->taskRepository->find($id);

        if (!$oldTask)
        {
            return new Response(Response::HTTP_NOT_FOUND);
        }

        if(!(is_null($task->getType()))){
            $oldTask->setType($task->getType());
        }

        if(!(is_null($task->getDescription()))){
            $oldTask->setDescription($task->getDescription());
        }

        if(!(is_null($task->getLocation()))){
            $oldTask->setLocation($task->getLocation());
        }

        if(!(is_null($task->getTime()))){
            $oldTask->setTime($task->getTime());
        }

        $this->entityManager->flush();
    }
}
