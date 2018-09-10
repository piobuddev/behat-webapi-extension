<?php declare(strict_types=1);

namespace WebApi\HttpClient;

use Psr\Http\Message\ResponseInterface;

interface HttpClientInterface
{
    /**
     * @param string $method
     * @param string $url
     * @param array  $options
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function request(string $method, string $url, array $options = []): ResponseInterface;
}
