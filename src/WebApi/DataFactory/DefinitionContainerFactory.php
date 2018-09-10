<?php declare(strict_types=1);

namespace WebApi\DataFactory;

use RepositoryTester\DataFactory\Definition\Container\Container;
use RepositoryTester\DataFactory\Definition\Container\ContainerInterface;
use RepositoryTester\DataFactory\Definition\Loader\Loaders\PhpFileLoader;

class DefinitionContainerFactory
{
    /**
     * @param string $definitionPath
     * @param string $definitionsFile
     *
     * @return \RepositoryTester\DataFactory\Definition\Container\ContainerInterface
     */
    public static function create(string $definitionPath, string $definitionsFile): ContainerInterface
    {
        $container = new Container();
        $phpLoader = new PhpFileLoader($container, $definitionPath);
        $phpLoader->load($definitionsFile);

        return $container;
    }
}
