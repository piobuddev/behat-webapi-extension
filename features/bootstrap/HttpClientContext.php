<?php declare(strict_types=1);

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use WebApi\Context\Contexts\HttpClientContext as WebApiHttpClientContext;

class HttpClientContext extends WebApiHttpClientContext
{
    public function __construct()
    {
        $headers       = ['Content-Type' => 'application/json'];
        $responses     = [new Response(200, $headers, json_encode(['foo' => 'bar']))];
        $handlerStack  = MockHandler::createWithMiddleware($responses);
        $this->options = ['handler' => $handlerStack];
    }
}
