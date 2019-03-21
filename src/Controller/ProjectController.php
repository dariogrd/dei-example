<?php

namespace App\Controller;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    /**
     * @Route("/project-all", name="project_all")
     */
    public function all(): JsonResponse
    {
        $projects = $this->getDoctrine()->getRepository(Project::class)->findAll();
        return $this->json($projects);
    }
}
