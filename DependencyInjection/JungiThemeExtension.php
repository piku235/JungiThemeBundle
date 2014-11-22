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
        $this->configureThemeResolver('jungi_theme.resolver', 'primary', $config, $container);

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

        // Theme matcher service
        $container->setAlias('jungi_theme.matcher', $config['matcher']['id']);

        // Device theme filter
        if (!$config['matcher']['device_filter']) {
            $container->removeDefinition('jungi_theme.matcher.filter.device');
        }

        // Class cache
        $this->addClassesToCompile(array(
            'Jungi\Bundle\ThemeBundle\CacheWarmer\TemplateFinderChain',
            'Jungi\Bundle\ThemeBundle\CacheWarmer\ThemeFinder',
            'Jungi\Bundle\ThemeBundle\Core\MobileDetect',
            'Jungi\Bundle\ThemeBundle\Core\Theme',
            'Jungi\Bundle\ThemeBundle\Core\ThemeManager',
            'Jungi\Bundle\ThemeBundle\Core\ThemeNameReference',
            'Jungi\Bundle\ThemeBundle\Core\ThemeNameParser',
            'Jungi\Bundle\ThemeBundle\Information\ThemeInfoEssence',
            'Jungi\Bundle\ThemeBundle\Information\ThemeInfoEssenceBuilder',
            'Jungi\Bundle\ThemeBundle\Information\Author',
            'Jungi\Bundle\ThemeBundle\Changer\ThemeChanger',
            'Jungi\Bundle\ThemeBundle\Changer\ThemeChangerEvents',
            'Jungi\Bundle\ThemeBundle\Changer\Event\ChangeThemeEvent',
            'Jungi\Bundle\ThemeBundle\Event\HttpThemeEvent',
            'Jungi\Bundle\ThemeBundle\EventListener\ThemeHolderListener',
            'Jungi\Bundle\ThemeBundle\Mapping\Loader\FileLoader',
            'Jungi\Bundle\ThemeBundle\Mapping\Loader\PhpFileLoader',
            'Jungi\Bundle\ThemeBundle\Mapping\Loader\YamlFileLoader',
            'Jungi\Bundle\ThemeBundle\Mapping\Loader\LoaderHelper',
            'Jungi\Bundle\ThemeBundle\Matcher\ChainThemeMatcher',
            'Jungi\Bundle\ThemeBundle\Matcher\StandardThemeMatcher',
            'Jungi\Bundle\ThemeBundle\Matcher\VirtualThemeMatcher',
            'Jungi\Bundle\ThemeBundle\Matcher\Filter\DeviceThemeFilter',
            'Jungi\Bundle\ThemeBundle\Matcher\Filter\ThemeCollection',
            'Jungi\Bundle\ThemeBundle\Resolver\InMemoryThemeResolver',
            'Jungi\Bundle\ThemeBundle\Resolver\CookieThemeResolver',
            'Jungi\Bundle\ThemeBundle\Resolver\SessionThemeResolver',
            'Jungi\Bundle\ThemeBundle\Resolver\EventListener\ThemeResolverListener',
            'Jungi\Bundle\ThemeBundle\Selector\ThemeSelector',
            'Jungi\Bundle\ThemeBundle\Selector\ThemeSelectorEvents',
            'Jungi\Bundle\ThemeBundle\Selector\Event\ResolvedThemeEvent',
            'Jungi\Bundle\ThemeBundle\Selector\Event\DetailedResolvedThemeEvent',
            'Jungi\Bundle\ThemeBundle\Selector\EventListener\ValidationListener',
            'Jungi\Bundle\ThemeBundle\Validation\ValidationUtils',
            'Jungi\Bundle\ThemeBundle\Tag\DesktopDevices',
            'Jungi\Bundle\ThemeBundle\Tag\MobileDevices',
            'Jungi\Bundle\ThemeBundle\Tag\VirtualTheme',
            'Jungi\Bundle\ThemeBundle\Tag\TagCollection',
            'Jungi\Bundle\ThemeBundle\Tag\Factory\TagFactory',
            'Jungi\Bundle\ThemeBundle\Tag\Registry\TagRegistry',
            'Jungi\Bundle\ThemeBundle\Tag\Registry\TagProvider',
            'Jungi\Bundle\ThemeBundle\Helper\DeviceHelper',
            'Jungi\Bundle\ThemeBundle\Twig\Extension\DeviceExtension',
            'Jungi\Bundle\ThemeBundle\Templating\TemplateFilenameParser',
            'Jungi\Bundle\ThemeBundle\Templating\TemplateNameParser',
            'Jungi\Bundle\ThemeBundle\Templating\TemplateReference',
            'Jungi\Bundle\ThemeBundle\Templating\Loader\TemplateLocator',
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
