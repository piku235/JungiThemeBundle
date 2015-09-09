<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class JungiThemeExtension extends Extension
{
    /**
     * @var array
     */
    private $tagClasses;

    /**
     * @var array
     */
    private $mappingFiles;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->mappingFiles = array();
        $this->tagClasses = array();
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        // Config
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Services
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('fundamental.xml');
        $loader->load('templating.xml');
        $loader->load('extensions.xml');
        $loader->load('mappings.xml');
        $loader->load('listeners.xml');

        // Register theme mapping files
        $this->processThemeMappings($config, $container);

        // Register tag classes
        $this->registerTag($config['tags']);
        $tagClassRegistry = $container->getDefinition('jungi_theme.tag.class_registry');
        $tagClassRegistry->replaceArgument(0, $this->tagClasses);

        // Ignore null themes
        $container->setParameter('jungi_theme.listener.holder.ignore_null_theme', $config['holder']['ignore_null_theme']);

        // Primary theme resolver conf
        $this->processThemeResolver('jungi_theme.resolver.primary', 'primary', $config, $container);

        // Fallback theme resolver conf
        if (isset($config['resolver']['fallback'])) {
            $this->processThemeResolver('jungi_theme.resolver.fallback', 'fallback', $config, $container);
        }

        // Theme registry conf
        if (isset($config['source']['id'])) {
            $container->setAlias('jungi_theme.source', $config['source']['id']);
        }

        // Theme holder conf
        if (isset($config['holder']['id'])) {
            $container->setAlias('jungi_theme.holder', $config['holder']['id']);
        }

        // Theme selector service
        if (isset($config['selector']['id'])) {
            $container->setAlias('jungi_theme.selector', $config['selector']['id']);
        }

        // Validation listener
        if (!$config['selector']['validation_listener']['enabled']) {
            $container->removeDefinition('jungi_theme.selector.listener.validation');
        } else {
            $container->setParameter('jungi_theme.selector.listener.validation.suspects', $config['selector']['validation_listener']['suspects']);
        }

        // Device theme filter
        if (!$config['resolver']['virtual']['device_filter']) {
            $container->removeDefinition('jungi_theme.resolver.filter.device');
        }
    }

    /**
     * Registers a theme mapping file to load.
     *
     * @param string $file A theme mapping file
     * @param string $type A type of theme mapping file (optional)
     */
    public function registerMappingFile($file, $type = null)
    {
        $this->mappingFiles[] = array($file, $type);
    }

    /**
     * Registers a tag class or tag classes.
     *
     * @param string|array $class a collection or a single fully qualified class name
     *
     * @throws \RuntimeException         When the tag class is not exist
     * @throws \InvalidArgumentException When the given tag class does not implement the TagInterface
     */
    public function registerTag($class)
    {
        foreach ((array) $class as $child) {
            $child = '\\'.ltrim($child, '\\');
            if (!class_exists($child)) {
                throw new \RuntimeException(sprintf('The tag with the class "%s" is not exist.', $child));
            }

            $reflection = new \ReflectionClass($child);
            if (!$reflection->implementsInterface('Jungi\Bundle\ThemeBundle\Tag\TagInterface')) {
                throw new \InvalidArgumentException(
                    sprintf('The given class "%s" is not a tag. All tags must implement the "Jungi\Bundle\ThemeBundle\Tag\TagInterface".', $child)
                );
            }

            $this->tagClasses[$reflection->getMethod('getName')->invoke(null)] = $child;
        }
    }

    private function processThemeMappings(array $config, ContainerBuilder $container)
    {
        $mappingFiles = $this->mappingFiles;
        foreach ($config['mappings'] as $mapping) {
            $mappingFiles[] = array($mapping['resource'], $mapping['type']);
        }

        $def = $container->getDefinition('jungi_theme.source_initializer');
        $def->replaceArgument(0, $mappingFiles);
    }

    private function processThemeResolver($id, $for, array $config, ContainerBuilder $container)
    {
        list($type, $resolver) = each($config['resolver'][$for]);

        if ($type != 'id') {
            switch ($type) {
                case 'in_memory':
                    $definition = new Definition('Jungi\\Bundle\\ThemeBundle\\Resolver\\InMemoryThemeResolver');
                    $definition->addArgument($resolver);
                    break;
                case 'cookie':
                    $definition = new Definition('Jungi\\Bundle\\ThemeBundle\\Resolver\\CookieThemeResolver');
                    $definition->addArgument($resolver);
                    break;
                case 'session':
                    $definition = new Definition('Jungi\\Bundle\\ThemeBundle\\Resolver\\SessionThemeResolver');
                    break;
                default:
                    throw new \InvalidArgumentException('Not supported type.');
            }

            // Append the definition
            $container->setDefinition($id, $definition);
        } else {
            $container->setAlias($id, $resolver);
        }
    }
}
