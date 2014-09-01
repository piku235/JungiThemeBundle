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
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
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

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('fundamental.xml');
        $loader->load('extensions.xml');
        $loader->load('mappings.xml');
        $loader->load('form.xml');
        $loader->load('listeners.xml');

        // Ignore null themes
        $container->setParameter('jungi_theme.selector.options', array(
            'ignore_null_themes' => $config['selector']['ignore_null_themes']
        ));

        // Theme resolver conf
        $this->configureThemeResolver('jungi_theme.resolver', 'primary', $config, $container);

        // Theme resolver conf
        $this->configureFallbackThemeResolver($config, $container);

        // Theme holder conf
        $container->setAlias('jungi_theme.holder', $config['holder']['id']);

        // Validation listener
        if (!$config['selector']['validation_listener']['enabled']) {
            $container->removeDefinition('jungi_theme.selector.listener.validation');
        } elseif ($config['selector']['validation_listener']['use_investigator']) {
            $listener = $container->getDefinition('jungi_theme.selector.listener.validation');
            $listener->addArgument(new Reference('jungi_theme.resolver.investigator', ContainerInterface::NULL_ON_INVALID_REFERENCE));
        }

        // Device Theme Switch
        if (!$config['selector']['device_switch']['enabled']) {
            $container->removeDefinition('jungi_theme.selector.listener.device_switch');
        }

        // Investigator
        if (!$config['resolver']['investigator']['enabled']) {
            $container->removeDefinition('jungi_theme.resolver.investigator');
        } else {
            $container->setParameter('jungi_theme.resolver.investigator.suspects', $config['resolver']['investigator']['suspects']);
        }

        // Class cache
        $this->addClassesToCompile(array(
            'Jungi\Bundle\ThemeBundle\CacheWarmer\TemplateFinderChain',
            'Jungi\Bundle\ThemeBundle\CacheWarmer\ThemeFinder',
            'Jungi\Bundle\ThemeBundle\Core\MobileDetect',
            'Jungi\Bundle\ThemeBundle\Core\Details',
            'Jungi\Bundle\ThemeBundle\Core\ThemeFilenameParser',
            'Jungi\Bundle\ThemeBundle\Core\Theme',
            'Jungi\Bundle\ThemeBundle\Core\ThemeManager',
            'Jungi\Bundle\ThemeBundle\Core\ThemeReference',
            'Jungi\Bundle\ThemeBundle\Core\ThemeNameParser',
            'Jungi\Bundle\ThemeBundle\Form\Type\ThemeType',
            'Jungi\Bundle\ThemeBundle\Changer\StandardThemeChanger',
            'Jungi\Bundle\ThemeBundle\Changer\ThemeChangerEvents',
            'Jungi\Bundle\ThemeBundle\Core\Loader\ThemeLocator',
            'Jungi\Bundle\ThemeBundle\Event\ThemeEvent',
            'Jungi\Bundle\ThemeBundle\Mapping\Loader\FileLoader',
            'Jungi\Bundle\ThemeBundle\Mapping\Loader\PhpFileLoader',
            'Jungi\Bundle\ThemeBundle\Mapping\Loader\YamlFileLoader',
            'Jungi\Bundle\ThemeBundle\Mapping\Loader\LoaderHelper',
            'Jungi\Bundle\ThemeBundle\Resolver\InMemoryThemeResolver',
            'Jungi\Bundle\ThemeBundle\Resolver\CookieThemeResolver',
            'Jungi\Bundle\ThemeBundle\Resolver\SessionThemeResolver',
            'Jungi\Bundle\ThemeBundle\Resolver\EventListener\ThemeResolverListener',
            'Jungi\Bundle\ThemeBundle\Selector\StandardThemeSelector',
            'Jungi\Bundle\ThemeBundle\Selector\ThemeSelectorEvents',
            'Jungi\Bundle\ThemeBundle\Selector\Event\ResolvedThemeEvent',
            'Jungi\Bundle\ThemeBundle\Selector\Event\SmartResolvedThemeEvent',
            'Jungi\Bundle\ThemeBundle\Selector\EventListener\DeviceThemeSwitch',
            'Jungi\Bundle\ThemeBundle\Selector\EventListener\ValidationListener',
            'Jungi\Bundle\ThemeBundle\Selector\EventListener\ThemeHolderListener',
            'Jungi\Bundle\ThemeBundle\Validation\ValidationUtils',
            'Jungi\Bundle\ThemeBundle\Tag\DesktopDevices',
            'Jungi\Bundle\ThemeBundle\Tag\MobileDevices',
            'Jungi\Bundle\ThemeBundle\Tag\Link',
            'Jungi\Bundle\ThemeBundle\Tag\TagCollection',
            'Jungi\Bundle\ThemeBundle\Tag\Factory\TagFactory',
            'Jungi\Bundle\ThemeBundle\Tag\Registry\TagRegistry',
            'Jungi\Bundle\ThemeBundle\Tag\Registry\TagProvider',
            'Jungi\Bundle\ThemeBundle\Helper\DeviceHelper',
            'Jungi\Bundle\ThemeBundle\Twig\Extension\DeviceExtension'
        ));
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
            $resolverClass = 'Jungi\\Bundle\\ThemeBundle\\Resolver\\' . $class;

            // Definition
            $definition = new Definition($resolverClass, $arguments);

            // Append the definition
            $container->setDefinition($id, $definition);
        } else {
            $container->setAlias($id, $resolver['id']);
        }
    }

    protected function configureFallbackThemeResolver($config, ContainerBuilder $container)
    {
        if (!$config['resolver']['fallback']['enabled']) {
            return;
        }

        // Configure fallback theme resolver using the base method
        $this->configureThemeResolver('jungi_theme.fallback_resolver', 'fallback', $config, $container);
    }
}
