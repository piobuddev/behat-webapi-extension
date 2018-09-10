<?php declare(strict_types=1);

namespace WebApi\HttpClient;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class GuzzleHttpClient implements HttpClientInterface
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * @param \GuzzleHttp\Client $client
     * @param array              $config
     */
    public function __construct(Client $client, array $config = [])
    {
        $this->client = $client;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array  $options
     *
     * @return mixed
     */
    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        $query            = ['testing' => true];
        $options['query'] = isset($options['query']) ? array_merge($options['query'], $query) : $query;

        return $this->client->request($method, $url, $options);
    }
}
