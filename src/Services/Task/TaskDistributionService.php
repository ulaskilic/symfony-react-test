<?php


namespace App\Services\Task;


use App\Entity\Task;
use App\Mock\DeveloperMock;
use App\Repository\TaskRepository;
use Carbon\Carbon;
use Carbon\CarbonInterface;

class TaskDistributionService
{
    /**
     * @var TaskRepository
     */
    private TaskRepository $repository;

    /**
     * Distribute task if task complexity match with developer level
     *
     * @param array $providers
     *
     * @return array
     */
    public function approach1($providers = []): array
    {
        $tasks = [];
        if (!empty($providers)) {
            $tasks = $this->repository->findBy(['provider' => $providers]);
        } else {
            $tasks = $this->repository->findAll();
        }


        $queue = new \SplQueue();
        foreach ($tasks as $task) {
            $queue->push($task);
        }
        // clean heap
        $tasks = [];

        $currentDate = Carbon::now()->startOfDay();

        $devList = DeveloperMock::list();

        $devBucket = [];

        foreach ($devList as $dev) {
            $devBucket[$dev['workLevel']] = array_merge($dev, [
                'current' => $currentDate->clone()->setHour(8)->setMinute(0)->setSecond(0),
                'remainingHours' => $dev['dailyWorkHours'],
                'tasks' => [],
                'totalDay' => 1
            ]);
        }

        while (!$queue->isEmpty()) {
            /**
             * @var $currentTask Task
             */
            $currentTask = $queue->shift();

            $appropriateDev = $devBucket[$currentTask->getComplexity()];


            $nextDay = false;
            if($appropriateDev['remainingHours'] !== 0) {
                if($currentTask->getEstimation() > $appropriateDev['remainingHours']) {
                    $appropriateDev['tasks'][] = [
                        'task' => $currentTask->getIdentifier(),
                        'start' => $appropriateDev['current']->toISOString(),
                        'complexity' => $currentTask->getComplexity(),
                        'end' => $appropriateDev['current']->addHours($appropriateDev['remainingHours'])->toISOString(),
                        'dev' => $appropriateDev['name']
                    ];
                    $currentTask->setEstimation($currentTask->getEstimation() - $appropriateDev['remainingHours']);
                    $appropriateDev['remainingHours'] = 0;
                    $nextDay = true;
                } else {
                    $appropriateDev['tasks'][] = [
                        'task' => $currentTask->getIdentifier(),
                        'start' => $appropriateDev['current']->toISOString(),
                        'complexity' => $currentTask->getComplexity(),
                        'end' => $appropriateDev['current']->addHours($currentTask->getEstimation())->toISOString(),
                        'dev' => $appropriateDev['name']
                    ];
                    $appropriateDev['remainingHours'] = $appropriateDev['remainingHours'] - $currentTask->getEstimation();
                    $currentTask->setEstimation(0);
                }
            } else {
                $nextDay = true;
            }


            if($nextDay) {
                $appropriateDev['current'] = $this->nextWorkDay($appropriateDev['current'])
                    ->setHour(8)->setMinute(0)->setSecond(0);
                $appropriateDev['remainingHours'] = $appropriateDev['dailyWorkHours'];
                $appropriateDev['totalDay']++;
                $queue->unshift($currentTask);
            }

            $devBucket[$currentTask->getComplexity()] = $appropriateDev;
        }

        $events = [];
        $devs = [];
        foreach ($devBucket as $key => $bucket) {
            $events = array_merge($events, $bucket['tasks']);
            unset($bucket['tasks']);
            $devs[] = $bucket;
        }

        return [
            'devs' => $devs,
            'tasks' => $events,
        ];
    }

    public function approach2()
    {
        $tasks = [];
        if (!empty($providers)) {
            $tasks = $this->repository->findBy(['provider' => $providers]);
        } else {
            $tasks = $this->repository->findAll();
        }


        $queue = new \SplQueue();
        foreach ($tasks as $task) {
            // Keep estimation multipled with complexity
            $task->setEstimation($task->getEstimation() * $task->getComplexity());
            $queue->push($task);
        }
        // clean heap
        $tasks = [];

        // Keep daily buckets
        $completedBuckets = [];
        $currentDate = Carbon::now()->startOfDay();

        $devList = DeveloperMock::list();

        $devBucket = [];

        foreach ($devList as $dev) {
            $devBucket[$dev['workLevel']] = array_merge($dev, [
                'current' => $currentDate->clone()->setHour(8)->setMinute(0)->setSecond(0),
                'remainingHours' => $dev['dailyWorkHours'] * $dev['workLevel'],
                'tasks' => [],
                'totalDay' => 1
            ]);
        }

        while (!$queue->isEmpty()) {
            /**
             * @var $currentTask Task
             */
            $currentTask = $queue->shift();

            foreach ($devBucket as $workLevel => $appropriateDev) {

                if($appropriateDev['remainingHours'] !== 0) {
                    if($currentTask->getEstimation() > $appropriateDev['remainingHours']) {
                        $appropriateDev['tasks'][] = [
                            'task' => $currentTask->getIdentifier(),
                            'start' => $appropriateDev['current']->toISOString(),
                            'complexity' => $currentTask->getComplexity(),
                            'end' => $appropriateDev['current']->addSeconds($appropriateDev['remainingHours'] / $workLevel * 60 * 60)->toISOString(),
                            'dev' => $appropriateDev['name']
                        ];
                        $currentTask->setEstimation($currentTask->getEstimation() - $appropriateDev['remainingHours']);
                        $appropriateDev['remainingHours'] = 0;
                    } else {
                        $appropriateDev['tasks'][] = [
                            'task' => $currentTask->getIdentifier(),
                            'start' => $appropriateDev['current']->toISOString(),
                            'complexity' => $currentTask->getComplexity(),
                            'end' => $appropriateDev['current']->addSeconds($currentTask->getEstimation() / $workLevel * 60 * 60)->toISOString(),
                            'dev' => $appropriateDev['name']
                        ];
                        $appropriateDev['remainingHours'] = $appropriateDev['remainingHours'] - $currentTask->getEstimation();
                        $currentTask->setEstimation(0);
                    }
                }
                $devBucket[$workLevel] = $appropriateDev;
            }


            if($currentTask->getEstimation() !== 0) {
                foreach ($devBucket as $workLevel => $appropriateDev) {
                    $appropriateDev['current'] = $this->nextWorkDay($appropriateDev['current'])
                        ->setHour(8)->setMinute(0)->setSecond(0);
                    $appropriateDev['remainingHours'] = $appropriateDev['dailyWorkHours'] * $appropriateDev['workLevel'];
                    $appropriateDev['totalDay']++;
                    $devBucket[$workLevel] = $appropriateDev;
                }
                $queue->unshift($currentTask);
            }
        }

        $events = [];
        $devs = [];
        foreach ($devBucket as $key => $bucket) {
            $events = array_merge($events, $bucket['tasks']);
            unset($bucket['tasks']);
            $devs[] = $bucket;
        }

        return [
            'devs' => $devs,
            'tasks' => $events,
        ];
    }

    private function nextWorkDay(CarbonInterface $currentDay): CarbonInterface
    {
        $currentDay = $currentDay->clone()->addDay();
        if($currentDay->isWeekend()) {
            $currentDay = $this->nextWorkDay($currentDay->addDay());
        }
        return $currentDay;
    }

    /**
     * Autowired dependency
     * @required
     *
     * @param TaskRepository $taskRepository
     */
    public function setRepository(TaskRepository $taskRepository)
    {
        $this->repository = $taskRepository;
    }
}