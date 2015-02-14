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

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * ThemeFilterPass
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeFilterPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('jungi_theme.matcher.set')) {
            return;
        }

        $definition = $container->getDefinition('jungi_theme.matcher.set');
        foreach ($container->findTaggedServiceIds('jungi_theme.filter') as $id => $attrs) {
            $definition->addMethodCall('addFilter', array(new Reference($id)));
        }
    }
}
