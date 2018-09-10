<?php declare(strict_types=1);

namespace WebApi\Context\Initializers;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use RepositoryTester\DataFactory\Factories\Faker\FakerDataFactory;
use WebApi\Context\DataFactoryAwareContextInterface;

class DataFactoryAwareInitializer implements ContextInitializer
{
    /**
     * @var \RepositoryTester\DataFactory\Factories\Faker\FakerDataFactory
     */
    private $factory;

    /**
     * @param \RepositoryTester\DataFactory\Factories\Faker\FakerDataFactory $factory
     */
    public function __construct(FakerDataFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Initializes provided context.
     *
     * @param Context $context
     */
    public function initializeContext(Context $context)
    {
        if ($context instanceof DataFactoryAwareContextInterface) {
            $context->setDataFactory($this->factory);
        }
    }
}
