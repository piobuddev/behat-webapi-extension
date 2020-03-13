<?php declare(strict_types=1);

namespace WebApi\Context\Initializers;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use RepositoryTester\Repository\Connection\ConnectionInterface;
use WebApi\Context\RepositoryAwareContextInterface;

class RepositoryAwareInitializer implements ContextInitializer
{
    /**
     * @var \RepositoryTester\Repository\Connection\ConnectionInterface
     */
    private $connection;

    /**
     * @param \RepositoryTester\Repository\Connection\ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Initializes provided context.
     *
     * @param Context $context
     */
    public function initializeContext(Context $context): void
    {
        if ($context instanceof RepositoryAwareContextInterface) {
            $context->setConnection($this->connection);
        }
    }
}
