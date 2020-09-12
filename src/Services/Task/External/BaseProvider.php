<?php

namespace App\Services\Task\External;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class BaseProvider
{
    /**
     * HTTP Client
     *
     * @var HttpClientInterface
     */
    protected HttpClientInterface $client;

    /**
     * Base URL
     * @var string
     */
    private $url;

    public function __construct($url = "")
    {
        $this->url = $url;
        $this->client = HttpClient::createForBaseUri($this->url);
    }
}