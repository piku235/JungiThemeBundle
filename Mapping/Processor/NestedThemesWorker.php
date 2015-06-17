<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Mapping\Processor;

use Jungi\Bundle\ThemeBundle\Mapping\Reference;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinitionRegistryInterface;
use Jungi\Bundle\ThemeBundle\Mapping\VirtualThemeDefinition;

/**
 * This class reveals nested themes in virtual themes by moving them
 * to a theme definition registry.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class NestedThemesWorker implements WorkerInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ThemeDefinitionRegistryInterface $registry)
    {
        foreach ($registry->getThemeDefinitions() as $themeName => $theme) {
            if (!$theme instanceof VirtualThemeDefinition) {
                continue;
            }

            foreach ($theme->getThemes() as $name => $childTheme) {
                $uniqname = $themeName.uniqid(null, true);
                $theme->addThemeReference(new Reference($uniqname, $name));
                $registry->registerThemeDefinition($uniqname, $childTheme);

                // Removes the theme from the children themes as for now
                $theme->removeTheme($name);
            }
        }
    }
}
