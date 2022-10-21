<?php

namespace App\Controller;

use App\Entity\Task;
use Doctrine\Persistence\ManagerRegistry;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/api/task", name="api_task")
 */

class TaskController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/all", name="get_all_task")
     * @View(serializerGroups={"task"})
     */
    public function allTasks(ManagerRegistry $doctrine):JsonResponse
    {
        $tasks =$doctrine->getRepository(Task::class)->findAll();

        if (!$tasks) {
            throw $this->createNotFoundException(
                'No product found for id '
            );
        }

        //dd($tasks);

//        $encoders = [new JsonEncoder()];
//        $normalizers = [new ObjectNormalizer()];
//
//        $serializer = new Serializer($normalizers, $encoders);
//        $content = $serializer->serialize($tasks, 'json');

        return new JsonResponse($tasks);
    }
}

