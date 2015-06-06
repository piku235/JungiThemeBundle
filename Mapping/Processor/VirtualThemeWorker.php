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

use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinitionRegistryInterface;
use Jungi\Bundle\ThemeBundle\Mapping\VirtualThemeDefinition;

/**
 * VirtualThemeWorker.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class VirtualThemeWorker implements WorkerInterface
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

            // Move referenced theme definitions to a corresponding virtual theme
            foreach ($theme->getThemeReferences() as $referencedTheme) {
                $theme->addTheme($referencedTheme, $registry->getThemeDefinition($referencedTheme));

                // As a theme belongs now to the virtual theme we do not need it in the registry
                $registry->removeThemeDefinition($referencedTheme);
            }

            // Validate
            foreach ($theme->getThemes() as $childTheme) {
                if ($childTheme instanceof VirtualThemeDefinition) {
                    throw new \LogicException(sprintf(
                        'Virtual themes cannot consists of another virtual themes. Encountered at the theme "%s".',
                        $themeName
                    ));
                }
            }
        }
    }
}
