<?php


namespace App\Controller;


use App\Entity\Task;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    /**
     * @Route("/api/task", name="task-list")
     * @param TaskRepository $repository
     *
     * @return Response
     */
    public function index(TaskRepository $repository): Response
    {
        return $this->json($repository->findAll());
    }
}