<?php


namespace App\Services\Task;


use App\Resource\Task\TaskResource;
use App\Services\BaseService;
use App\Services\Task\External\TaskProvider2;
use App\Services\Task\External\TaskProviderInterface;

class Provider2Adapter extends BaseService implements TaskServiceInterface
{

    /**
     * Provider Flag
     */
    public const PROVIDER = 'mock-2';

    /**
     * Task Provider
     *
     * @var TaskProvider2|TaskProviderInterface
     */
    public TaskProviderInterface $provider;

    /**
     * TaskService constructor.
     *
     * @param $url
     */
    public function __construct($url)
    {
        $this->provider = new TaskProvider2($url);
    }

    /**
     * Get tasks from Mock 2
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
            $subfieldKeys = array_keys($value);
            if(isset($subfieldKeys[0])) {
                $entities[] = $this->mutateSingleTask($subfieldKeys[0], $value[$subfieldKeys[0]]);
            }
        }
        return $entities;
    }

    /**
     * Field mapping for Mock 2
     *
     * @param array $entity
     *
     * @return TaskResource
     */
    private function mutateSingleTask($key, $entity = []): TaskResource
    {
        return new TaskResource([
            'id' => $key,
            'complexity' => $entity['level'],
            'estimation' => $entity['estimated_duration'],
            'provider' => self::PROVIDER
        ]);
    }
}