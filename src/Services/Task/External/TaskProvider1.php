<?php


namespace App\Services\Task\External;


class TaskProvider1 extends BaseProvider implements TaskProviderInterface
{
    /**
     * TaskProvider1 constructor.
     *
     * @param $url
     */
    public function __construct($url)
    {
        parent::__construct($url);
    }

    /**
     * @return array
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