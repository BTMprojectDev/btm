<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
    private UserRepository $userRepository;

    public function __construct
    (
        EntityManagerInterface $entityManager,
        TaskRepository         $taskRepository,
        ParamFetcherInterface  $paramFetcher,
        UserRepository $userRepository
    )
    {
        $this->taskRepository = $taskRepository;
        $this->entityManager = $entityManager;
        $this->paramFetcher = $paramFetcher;
        $this->userRepository = $userRepository;
    }


    /**
     * @Rest\Get("", name="get_user_tasks")
     * @QueryParam(name="user_id", requirements="\d+", default="0", description="User id")
     * @QueryParam(name="task_id", requirements="\d+", default="0", description="Task id")
     * @Rest\View(serializerGroups={"task"}, statusCode=Response::HTTP_FOUND)
     */
    public function show()
    {
        $user_id = $this->paramFetcher->get("user_id");
        if($user_id != 0) {
            return $this->taskRepository->findBy(["user" => $user_id]);
        }

        return new JsonResponse(
            null,
            Response::HTTP_BAD_REQUEST
        );
    }

    /**
     * @Rest\Post("", name="post_task")
     * @ParamConverter("task", converter="fos_rest.request_body")
     * @RequestParam(name="user_id", requirements="\d+", description="User id")
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     */
    public function add(Task $task)
    {
        $user_id = $this->paramFetcher->get("user_id");
        $user = $this->userRepository->find($user_id);
        $user->addTask($task);
        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }


    /**
     * @Rest\Get("", name="get_all")
     * @Rest\View(serializerGroups={"task"})
     */
    public function all()
    {
        return $this->taskRepository->findAll();
    }



    /**
     * @Rest\Delete("", name="del_by_id")
     * @QueryParam(name="task_id", requirements="\d+", default="0", description="User id")
     * @Rest\View(statusCode=Response::HTTP_OK)
     */
    public function del()
    {
        $task_id = $this->paramFetcher->get("task_id");
        $task = $this->taskRepository->find($task_id);
        $this->entityManager->remove($task);
        $this->entityManager->flush();
    }

    /**
     * @Rest\Patch("", name="modify_task_by_id")
     * @QueryParam(name="task_id", requirements="\d+", default="0", description="task_id")
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     */
    public function modify(Request $request)
    {
        $id = $this->paramFetcher->get('task_id');
        $oldTask = $this->taskRepository->find($id);
        $newTask = $request->request->all();
        $form = $this->createForm(TaskType::class,$oldTask);
        $form->submit($newTask,false);

        if(false === $form->isValid()){
            dd($form->getErrors(true)->current());
        }

        $this->entityManager->flush();
    }

    /**
     * @Rest\Put("", name="update_task_by_id")
     * @QueryParam(name="task_id", requirements="\d+", default="0", description="task_id")
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     */
    public function change(Request $request)
    {
        $id = $this->paramFetcher->get('task_id');
        $oldTask = $this->taskRepository->find($id);
        $newTask = $request->request->all();
        $form = $this->createForm(TaskType::class,$oldTask);
        $form->submit($newTask,false);

        if(false === $form->isValid()){
            dd($form->getErrors(true)->current());
        }

        $this->entityManager->flush();
    }
}
