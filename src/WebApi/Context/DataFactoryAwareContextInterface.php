<?php declare(strict_types=1);

namespace WebApi\Context;

use RepositoryTester\DataFactory\Factories\Faker\FakerDataFactory;

interface DataFactoryAwareContextInterface
{
    /**
     * @param \RepositoryTester\DataFactory\Factories\Faker\FakerDataFactory $factory
     */
    public function setDataFactory(FakerDataFactory $factory): void;
}
