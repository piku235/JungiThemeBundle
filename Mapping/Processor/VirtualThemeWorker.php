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
 * VirtualThemeWorker processes virtual theme definitions.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class VirtualThemeWorker implements WorkerInterface
{
    /**
     * {@inheritdoc}
     *
     * @throws \LogicException When one theme is attached to many virtual themes
     * @throws \LogicException If virtual theme is referenced by another virtual theme
     */
    public function process(ThemeDefinitionRegistryInterface $registry)
    {
        $usedReferences = array();
        foreach ($registry->getThemeDefinitions() as $themeName => $theme) {
            if (!$theme instanceof VirtualThemeDefinition) {
                continue;
            }

            // Move referenced theme definitions to a corresponding virtual theme
            foreach ($theme->getThemeReferences() as $reference) {
                if (isset($usedReferences[$reference->getThemeName()])) {
                    throw new \LogicException(sprintf(
                        'The theme "%s" is currently attached to the virtual theme "%s". You cannot attach the same theme to several virtual themes.',
                        $reference->getThemeName(),
                        $usedReferences[$reference->getThemeName()]
                    ));
                }

                $name = $reference->getAlias() ?: $reference->getThemeName();
                $theme->addTheme($name, $registry->getThemeDefinition($reference->getThemeName()));

                // As a theme belongs now to the virtual theme we do not need it in the registry
                $registry->removeThemeDefinition($reference->getThemeName());
                $usedReferences[$reference->getThemeName()] = $themeName;
            }

            // Clear theme references
            $theme->clearThemeReferences();

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
