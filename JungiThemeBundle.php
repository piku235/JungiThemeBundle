<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle;

use Jungi\Bundle\ThemeBundle\DependencyInjection\Compiler\ThemeFilterPass;
use Jungi\Bundle\ThemeBundle\DependencyInjection\Compiler\CacheWarmerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * The jungi theme bundle.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class JungiThemeBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new CacheWarmerPass());
        $container->addCompilerPass(new ThemeFilterPass());

        /** @var DependencyInjection\JungiThemeExtension $ext */
        $ext = $this->getContainerExtension();

        // Default tags
        $ext->registerTag(array(
            'Jungi\Bundle\ThemeBundle\Tag\MobileDevices',
            'Jungi\Bundle\ThemeBundle\Tag\TabletDevices',
            'Jungi\Bundle\ThemeBundle\Tag\DesktopDevices',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->container->get('jungi_theme.source_initializer')->initialize();
    }
}
