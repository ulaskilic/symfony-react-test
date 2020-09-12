<?php


namespace App\Services\Task;


interface TaskServiceFactoryInterface
{
    public static function create($url, $service): TaskServiceInterface;
    public static function services(): iterable;
}