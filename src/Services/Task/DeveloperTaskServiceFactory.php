<?php

namespace App\Services\Task;

use App\Resource\Task\TaskResource;

class DeveloperTaskServiceFactory implements TaskServiceFactoryInterface
{
    /**
     * Create Task service
     *
     * @param        $url
     * @param string $service
     *
     * @return TaskServiceInterface
     */
    public static function create($url, $service = TaskService::class): TaskServiceInterface
    {
        return new $service($url);
    }

    /**
     * Task services
     *
     * @return iterable
     */
    public static function services(): iterable
    {
        return [
            TaskService::class,
            Provider2Adapter::class
        ];
    }
}