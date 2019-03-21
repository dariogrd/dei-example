<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use App\Entity\TaskType;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    public function index(SerializerInterface $serializer): Response
    {
        $tasks = $this->getDoctrine()->getRepository(Task::class)->findAll();

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
            'tasksJson' => $serializer->serialize($tasks, 'json')
        ]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $task = new Task();
        $task->setProject($em->getRepository(Project::class)->find($request->get('project')));
        $task->setType($em->getRepository(TaskType::class)->find($request->get('type')));
        $task->setTitle($request->get('title'));
        if(!empty($request->get('assignee'))) $task->setAssignee($em->getRepository(User::class)->find($request->get('assignee')));
        if(!empty($request->get('dueDate'))) $task->setDueDate(new \DateTime($request->get('dueDate')));
        $task->setDescription($request->get('description'));
        $task->setCreatedBy($this->getUser());
        $em->persist($task);
        $em->flush();

        return $this->json($task);
    }
}
