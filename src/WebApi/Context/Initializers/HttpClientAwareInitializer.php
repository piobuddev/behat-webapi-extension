<?php declare(strict_types=1);

namespace WebApi\Context\Initializers;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use WebApi\Context\HttpClientAwareContextInterface;
use WebApi\HttpClient\HttpClientInterface;

class HttpClientAwareInitializer implements ContextInitializer
{
    /**
     * @var \WebApi\HttpClient\HttpClientInterface
     */
    private $httpClient;

    /**
     * @var array
     */
    private $config;

    /**
     * @param \WebApi\HttpClient\HttpClientInterface $httpClient
     * @param array                                  $config
     */
    public function __construct(HttpClientInterface $httpClient, array $config)
    {
        $this->httpClient = $httpClient;
        $this->config     = $config;
    }

    /**
     * Initializes provided context.
     *
     * @param Context $context
     */
    public function initializeContext(Context $context): void
    {
        if ($context instanceof HttpClientAwareContextInterface) {
            $context->setClient($this->httpClient);
        }
    }
}
