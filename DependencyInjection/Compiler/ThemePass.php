<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * ThemePass
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemePass implements CompilerPassInterface
{
    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface::process()
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('templating.cache_warmer.template_paths')) {
            $cacheWarmer = $container->getDefinition('templating.cache_warmer.template_paths');
            $cacheWarmer->replaceArgument(0, new Reference('jungi.theme.cache_warmer.finder.chain'));
        }

        $manager = $container->getDefinition('jungi.theme.manager');
        foreach ($container->findTaggedServiceIds('jungi.theme') as $id => $attrs) {
            $manager->addMethodCall('addTheme', array(new Reference($id)));
        }
    }
}