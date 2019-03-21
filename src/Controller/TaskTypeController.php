<?php

namespace App\Controller;

use App\Entity\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TaskTypeController extends AbstractController
{
    /**
     * @Route("/task-type-all", name="task_type_all")
     */
    public function all(): JsonResponse
    {
        $types = $this->getDoctrine()->getRepository(TaskType::class)->findAll();
        return $this->json($types);
    }
}
