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
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class JungiThemeExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('templating.xml');
        $loader->load('fundamental.xml');
        $loader->load('extensions.xml');
        $loader->load('mappings.xml');
        $loader->load('listeners.xml');

        // Ignore null themes
        $container->setParameter('jungi_theme.listener.holder.ignore_null_theme', $config['holder']['ignore_null_theme']);

        // Primary theme resolver conf
        $this->configureThemeResolver('jungi_theme.resolver.primary', 'primary', $config, $container);

        // Fallback theme resolver conf
        if ($config['resolver']['fallback']['enabled']) {
            $this->configureThemeResolver('jungi_theme.fallback_resolver', 'fallback', $config, $container);
        }

        // Theme holder conf
        $container->setAlias('jungi_theme.holder', $config['holder']['id']);

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
        if (!$config['matcher']['device_filter']) {
            $container->removeDefinition('jungi_theme.matcher.filter.device');
        }
    }

    protected function configureThemeResolver($id, $for, $config, ContainerBuilder $container)
    {
        $resolver = $config['resolver'][$for];
        if ($resolver['type'] != 'service') {
            $arguments = $resolver['arguments'] ? (array) $resolver['arguments'] : array();
            switch ($resolver['type']) {
                case 'in_memory':
                    $class = 'InMemoryThemeResolver';
                    break;
                case 'cookie':
                    $class = 'CookieThemeResolver';
                    break;
                case 'session':
                    $class = 'SessionThemeResolver';
                    break;
                default:
                    throw new \InvalidArgumentException('Not supported type.');
            }

            // Class
            $resolverClass = 'Jungi\\Bundle\\ThemeBundle\\Resolver\\'.$class;

            // Definition
            $definition = new Definition($resolverClass, $arguments);

            // Append the definition
            $container->setDefinition($id, $definition);
        } else {
            $container->setAlias($id, $resolver['id']);
        }
    }
}
