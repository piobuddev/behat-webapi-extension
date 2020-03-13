<?php declare(strict_types=1);

namespace WebApi\ServiceContainer;

use Behat\Testwork\ServiceContainer\Extension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Dotenv\Dotenv;

class WebApiExtension implements Extension
{
    private const CONFIG_PATH = __DIR__ . '/../../../config';

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        return;
    }

    /**
     * Returns the extension config key.
     *
     * @return string
     */
    public function getConfigKey(): string
    {
        return 'web_api';
    }

    /**
     * Initializes other extensions.
     *
     * This method is called immediately after all extensions are activated but
     * before any extension `configure()` method is called. This allows extensions
     * to hook into the configuration of other extensions providing such an
     * extension point.
     *
     * @param ExtensionManager $extensionManager
     */
    public function initialize(ExtensionManager $extensionManager): void
    {
        return;
    }

    /**
     * Setups configuration for the extension.
     *
     * @param ArrayNodeDefinition $builder
     */
    public function configure(ArrayNodeDefinition $builder): void
    {
        $builder
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('http_client')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('base_uri')
                            ->defaultValue('http://localhost')
                        ->end()
                        ->booleanNode('verify')
                            ->defaultFalse()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('repository_tester')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('definitions_path')
                            ->defaultValue('storage/database/factory')
                        ->end()
                        ->booleanNode('verify')
                            ->defaultFalse()
                        ->end()
                        ->scalarNode('db_config_file')
                            ->defaultValue('.env_testing')
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * Loads extension services into temporary container.
     *
     * @param ContainerBuilder $container
     * @param array            $config
     *
     * @throws \Exception
     */
    public function load(ContainerBuilder $container, array $config): void
    {
        $basePath       = $container->getParameter('paths.base');
        $definitionPath = $basePath . '/' . $config['repository_tester']['definitions_path'];

        (new Dotenv())->load($basePath . '/' . $config['repository_tester']['db_config_file']);

        $container->setParameter('DB_HOST', getenv('DB_HOST'));
        $container->setParameter('DB_DATABASE', getenv('DB_DATABASE'));
        $container->setParameter('DB_USERNAME', getenv('DB_USERNAME'));
        $container->setParameter('DB_PASSWORD', getenv('DB_PASSWORD'));
        $container->setParameter('web_api.http_client.config', $config['http_client']);
        $container->setParameter('web_api.definitions.path', $definitionPath);

        $loader = new YamlFileLoader($container, new FileLocator(self::CONFIG_PATH));
        $loader->load('http_client.yaml');
        $loader->load('repository_tester.yaml');
    }
}
