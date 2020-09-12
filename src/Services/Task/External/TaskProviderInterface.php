<?php


namespace App\Services\Task\External;


interface TaskProviderInterface
{
    public function __construct($url);

    public function taskList(): iterable;
}