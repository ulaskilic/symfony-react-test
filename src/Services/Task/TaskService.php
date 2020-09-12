<?php

namespace App\Services\Task;

use App\Resource\Task\TaskResource;
use App\Services\BaseService;
use App\Services\Task\External\TaskProvider1;
use App\Services\Task\External\TaskProviderInterface;

class TaskService extends BaseService implements TaskServiceInterface
{

    /**
     * Provider Flag
     */
    public const PROVIDER = 'mock-1';

    /**
     * Task Provider
     *
     * @var TaskProvider1|TaskProviderInterface
     */
    public TaskProviderInterface $provider;

    /**
     * TaskService constructor.
     *
     * @param $url
     */
    public function __construct($url)
    {
        $this->provider = new TaskProvider1($url);
    }

    /**
     * Get tasks from Mock 1
     *
     * @return TaskResource[]
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     */
    public function getTasks(): iterable
    {
        $entities = [];
        $taskList = $this->provider->taskList();
        foreach ($taskList as $key => $value) {
            $entities[] = $this->mutateSingleTask($value);
        }
        return $entities;
    }

    /**
     * Field mapping for Mock 1
     *
     * @param array $entity
     *
     * @return TaskResource
     */
    private function mutateSingleTask($entity = []): TaskResource
    {
        return new TaskResource([
            'id' => $entity['id'],
            'complexity' => $entity['zorluk'],
            'estimation' => $entity['sure'],
            'provider' => self::PROVIDER
        ]);
    }
}