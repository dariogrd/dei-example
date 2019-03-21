<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/task", name="task_")
 */
class TaskController extends AbstractController
{
    /**
     * @Route("/", name="list")
     */
    public function index(SerializerInterface $serializer)
    {
        $tasks = $this->getDoctrine()->getRepository(Task::class)->findAll();

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
            'tasksJson' => $serializer->serialize($tasks, 'json')
        ]);
    }
}
