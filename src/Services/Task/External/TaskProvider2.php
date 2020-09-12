<?php


namespace App\Services\Task\External;


class TaskProvider2 extends BaseProvider implements TaskProviderInterface
{
    /**
     * TaskProvider2 constructor.
     *
     * @param $url
     */
    public function __construct($url)
    {
        parent::__construct($url);
    }

    /**
     * @return iterable
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function taskList(): iterable
    {
        return $this->client->request('GET', '')->toArray();
    }
}