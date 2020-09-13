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
     * @Route("/api/debug", name="dev-list")
     * @param TaskDistributionService $distService
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function debug(TaskDistributionService $distService)
    {
        $today = Carbon::now()->startOfDay();



        return $this->json($distService->distributeTasks());
    }
}
