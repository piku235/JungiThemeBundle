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
 * TagProviderPass
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class TagProviderPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('jungi_theme.tag.registry')) {
            return;
        }

        $tagFactory = $container->getDefinition('jungi_theme.tag.registry');
        foreach ($container->findTaggedServiceIds('jungi_theme.tag_provider') as $id => $attrs) {
            $tagFactory->addMethodCall('register', array(new Reference($id)));
        }
    }
}
