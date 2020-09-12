<?php

namespace App\Resource\Task;

use App\Resource\BaseResource;

/**
 * Class TaskResource
 *
 * @package App\Resource\Task
 */
class TaskResource extends BaseResource
{

    /**
     * Task ID
     *
     * @var string
     */
    public string $id;

    /**
     * Complexity level
     *
     * @var int
     */
    public int $complexity;

    /**
     * Estimated hour
     *
     * @var int
     */
    public int $estimation;

    /**
     * Task Provider
     *
     * @var string
     */
    public string $provider;

    /**
     * TaskResource constructor.
     *
     * @param array $raw
     */
    public function __construct($raw = [])
    {
        $this->fill($raw);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getComplexity(): int
    {
        return $this->complexity;
    }

    /**
     * @param int $complexity
     */
    public function setComplexity(int $complexity): void
    {
        $this->complexity = $complexity;
    }

    /**
     * @return int
     */
    public function getEstimation(): int
    {
        return $this->estimation;
    }

    /**
     * @param int $estimation
     */
    public function setEstimation(int $estimation): void
    {
        $this->estimation = $estimation;
    }

    /**
     * @return string
     */
    public function getProvider(): string
    {
        return $this->provider;
    }

    /**
     * @param string $provider
     */
    public function setProvider(string $provider): void
    {
        $this->provider = $provider;
    }

}