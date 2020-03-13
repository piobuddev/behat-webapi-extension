<?php declare(strict_types=1);

namespace WebApi\Context\Contexts;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use RepositoryTester\DataFactory\Factories\Faker\FakerDataFactory;
use RepositoryTester\Repository\Connection\ConnectionInterface;
use RepositoryTester\RepositoryAssertTrait;
use WebApi\Context\DataFactoryAwareContextInterface;
use WebApi\Context\RepositoryAwareContextInterface;

class RepositoryContext implements RepositoryAwareContextInterface, DataFactoryAwareContextInterface, Context
{
    use RepositoryAssertTrait {
        disableForeignKeys as traitDisableForeignKeys;
        enableForeignKeys as traitEnableForeignKeys;
    }

    /**
     * @var \RepositoryTester\Repository\Connection\ConnectionInterface
     */
    private $connection;

    /**
     * @var \RepositoryTester\DataFactory\Factories\Faker\FakerDataFactory
     */
    private $factory;

    /**
     * @param \RepositoryTester\Repository\Connection\ConnectionInterface $connection
     */
    public function setConnection(ConnectionInterface $connection): void
    {
        $this->connection = $connection;
    }

    /**
     * @param \RepositoryTester\DataFactory\Factories\Faker\FakerDataFactory $factory
     */
    public function setDataFactory(FakerDataFactory $factory): void
    {
        $this->factory = $factory;
    }

    /**
     * @Given /^(?:the )?following ([a-z\_]+)(\(s\))? exist:$/
     *
     * @param string                        $repository
     * @param \Behat\Gherkin\Node\TableNode $table
     */
    public function entitiesExists(string $repository, TableNode $table): void
    {
        $data = $table->getHash();

        $this->insert([$repository => $this->parseValues($data)]);
    }

    /**
     * @Then /^the following ([a-z]+) should be saved:$/
     *
     * @param string                        $repository
     * @param \Behat\Gherkin\Node\TableNode $table
     */
    public function databaseContains(string $repository, TableNode $table): void
    {
        $this->assertRepositoryHasRows($repository, $table->getHash());
    }

    /**
     * @Then /^the following ([a-z]+) should not be saved:$/
     *
     * @param string                        $repository
     * @param \Behat\Gherkin\Node\TableNode $table
     */
    public function databaseNotContains(string $repository, TableNode $table): void
    {
        $this->assertRepositoryDoesNotHaveRow($repository, $table->getHash()[0]);
    }

    /**
     * @BeforeScenario @repository
     * @AfterScenario @repository
     */
    public function cleanRepository(): void
    {
        $this->clearTables();
    }

    /**
     * @BeforeScenario @disableForeignKeys
     */
    public function disableForeignKeys(): void
    {
        $this->traitDisableForeignKeys();
    }

    /**
     * @AfterScenario @disableForeignKeys
     */
    public function enableForeignKeys(): void
    {
        $this->traitEnableForeignKeys();
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function parseValues(array $data): array
    {
        array_walk_recursive(
            $data,
            function (&$row) {
                $row = ($row === 'null') ? null : $row;
            }
        );

        return $data;
    }
}
