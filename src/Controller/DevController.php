<?php

namespace App\Controller;

use App\Mock\DeveloperMock;
use App\Services\Task\TaskDistributionService;
use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DevController extends AbstractController
{
    /**
     * @Route("/api/dev", name="dev-list")
     */
    public function index()
    {
        return $this->json(DeveloperMock::list());
    }

    /**
     * @Route("/api/approach1", name="approach1")
     * @param TaskDistributionService $distService
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function approach1(TaskDistributionService $distService)
    {
        return $this->json($distService->approach1());
    }

    /**
     * @Route("/api/approach2", name="approach2")
     * @param TaskDistributionService $distService
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function approach2(TaskDistributionService $distService)
    {
        return $this->json($distService->approach2());
    }
}
