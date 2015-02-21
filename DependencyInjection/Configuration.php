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

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * The main configuration of the JungiThemeBundle
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('jungi_theme');

        $rootNode
            ->children()
                ->append($this->addThemeHolderNode())
                ->append($this->addThemeMatcherNode())
                ->append($this->addThemeSelectorNode())
                ->append($this->addThemeResolverNode())
            ->end();

        return $treeBuilder;
    }

    protected function addThemeHolderNode()
    {
        $builder = new TreeBuilder();
        $rootNode = $builder->root('holder');

        $rootNode
            ->addDefaultsIfNotSet()
            ->info('theme holder configuration')
            ->children()
                ->scalarNode('id')
                    ->defaultValue('jungi_theme.holder.default')
                    ->info('theme holder service id')
                ->end()
                ->booleanNode('ignore_null_theme')
                    ->defaultTrue()
                    ->info('whether to ignore the situation when the theme selector will not match any theme for the request.')
                ->end()
            ->end();

        return $rootNode;
    }

    protected function addThemeMatcherNode()
    {
        $builder = new TreeBuilder();
        $rootNode = $builder->root('matcher');

        $rootNode
            ->addDefaultsIfNotSet()
            ->info('theme matcher configuration')
            ->children()
                ->booleanNode('device_filter')
                    ->defaultTrue()
                    ->info('use the device theme filter')
                ->end()
            ->end();

        return $rootNode;
    }

    protected function addThemeSelectorNode()
    {
        $builder = new TreeBuilder();
        $rootNode = $builder->root('selector');
        $resolverNorm = function ($v) {
            if (isset($v['suspects'])) {
                array_walk($v['suspects'], function (&$class) {
                    if (false === strpos($class, '\\')) {
                        $class = 'Jungi\\Bundle\\ThemeBundle\\Resolver\\'.$class;
                    }

                    if (!class_exists($class)) {
                        throw new \InvalidArgumentException(sprintf('The theme resolver "%s" can not be found.', $class));
                    }
                });
            }

            return $v;
        };

        $rootNode
            ->addDefaultsIfNotSet()
            ->info('theme selector configuration')
            ->children()
                ->scalarNode('id')
                    ->cannotBeEmpty()
                    ->info('theme selector service id')
                ->end()
                ->arrayNode('validation_listener')
                ->info('theme validation listener configuration')
                    ->addDefaultsIfNotSet()
                    ->canBeEnabled()
                    ->fixXmlConfig('suspect')
                    ->children()
                        ->arrayNode('suspects')
                            ->info('a list of theme resolvers which should be validated')
                            ->prototype('scalar')->cannotBeEmpty()->end()
                        ->end()
                    ->end()
                    ->beforeNormalization()
                        ->always()
                        ->then($resolverNorm)
                    ->end()
                ->end()
            ->end();

        return $rootNode;
    }

    protected function addThemeResolverNode()
    {
        $builder = new TreeBuilder();
        $rootNode = $builder->root('resolver');

        $rootNode
            ->addDefaultsIfNotSet()
            ->isRequired()
            ->info('general theme resolver configuration')
            ->children()
                ->append($this->addPrimaryThemeResolverNode())
                ->append($this->addFallbackThemeResolverNode())
            ->end();

        return $rootNode;
    }

    protected function addFallbackThemeResolverNode()
    {
        $builder = new TreeBuilder();
        $rootNode = $builder->root('fallback');

        $rootNode
            ->info('fallback theme resolver configuration')
            ->canBeUnset();

        $this->configureThemeResolverNode($rootNode, true);

        return $rootNode;
    }

    protected function addPrimaryThemeResolverNode()
    {
        $builder = new TreeBuilder();
        $rootNode = $builder->root('primary');

        $rootNode
            ->info('theme resolver configuration')
            ->isRequired();

        $this->configureThemeResolverNode($rootNode);

        return $rootNode;
    }

    protected function configureThemeResolverNode(ArrayNodeDefinition $node)
    {
        $selfNode = $node
            ->beforeNormalization()
                ->ifString()
                ->then(function ($v) {
                    return array(
                        'id' => $v,
                    );
                })
            ->end()
            ->children();

        // Service
        $selfNode
            ->scalarNode('id')
            ->info('theme resolver service')
            ->cannotBeEmpty()
            ->end();

        // Cookie
        $selfNode
            ->arrayNode('cookie')
                ->info('cookie theme resolver')
                ->canBeUnset()
                ->children()
                    ->integerNode('lifetime')->defaultValue(2592000)->end()
                    ->scalarNode('path')->defaultValue('/')->end()
                    ->scalarNode('domain')->end()
                    ->booleanNode('secure')->defaultFalse()->end()
                    ->booleanNode('httpOnly')->defaultTrue()->end()
                ->end()
            ->end();

        // InMemory
        $selfNode
            ->scalarNode('in_memory')
                ->info('in memory theme resolver')
                ->cannotBeEmpty()
            ->end();

        // Session
        $selfNode
            ->booleanNode('session')
                ->info('session theme resolver')
                ->validate()
                    ->ifTrue(function ($v) {
                        return $v === false;
                    })
                    ->thenUnset()
                ->end()
            ->end();

        // Validation
        $selfNode
            ->end()
            ->validate()
                ->ifTrue(function ($v) {
                    return count($v) > 1;
                })
                ->thenInvalid('You cannot use more than one theme resolver.')
            ->end();
    }
}
