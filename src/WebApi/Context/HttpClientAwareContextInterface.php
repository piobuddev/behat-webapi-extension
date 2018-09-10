<?php declare(strict_types=1);

namespace WebApi\Context;

use Behat\Behat\Context\Context;
use WebApi\HttpClient\HttpClientInterface;

interface HttpClientAwareContextInterface extends Context
{
    /**
     * @param \WebApi\HttpClient\HttpClientInterface $httpClient
     */
    public function setClient(HttpClientInterface $httpClient): void;
}
