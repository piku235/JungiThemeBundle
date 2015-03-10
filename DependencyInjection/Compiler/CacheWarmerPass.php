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
 * CacheWarmerPass.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class CacheWarmerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('templating.cache_warmer.template_paths')) {
            return;
        }

        $cacheWarmer = $container->getDefinition('templating.cache_warmer.template_paths');
        $cacheWarmer->replaceArgument(0, new Reference('jungi_theme.cache_warmer.composite_finder'));
    }
}
