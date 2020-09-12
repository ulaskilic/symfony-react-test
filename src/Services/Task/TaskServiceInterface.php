<?php


namespace App\Services\Task;


use App\Resource\Task\TaskResource;

interface TaskServiceInterface
{
    public function __construct($url);

    /**
     * @return TaskResource[]
     */
    public function getTasks(): iterable;

}