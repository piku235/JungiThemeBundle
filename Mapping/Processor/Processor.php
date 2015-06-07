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

use Jungi\Bundle\ThemeBundle\Mapping\ContainerInterface;
use Jungi\Bundle\ThemeBundle\Mapping\StandardThemeDefinition;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinitionRegistryInterface;
use Jungi\Bundle\ThemeBundle\Mapping\VirtualThemeDefinition;

/**
 * Processor.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class Processor implements ProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ThemeDefinitionRegistryInterface $registry)
    {
        $this->processParameters($registry);
        $this->processVirtualThemes($registry);
    }

    /**
     * Processes virtual theme definitions
     *
     * @param ThemeDefinitionRegistryInterface $registry
     *
     * @throws \LogicException When one theme is attached to many virtual themes
     * @throws \LogicException If virtual theme is referenced by another virtual theme
     */
    private function processVirtualThemes(ThemeDefinitionRegistryInterface $registry)
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

    /**
     * Processes parameters contained in a registry
     *
     * @param ThemeDefinitionRegistryInterface $registry
     */
    private function processParameters(ThemeDefinitionRegistryInterface $registry)
    {
        if (!$registry instanceof ContainerInterface || !$registry->getParameters()) {
            return;
        }

        foreach ($registry->getThemeDefinitions() as $theme) {
            if ($theme instanceof StandardThemeDefinition) {
                $theme->setPath($this->resolveParameter($theme->getPath(), $registry));
            }

            // Tags
            foreach ($theme->getTags() as $tag) {
                $args = $tag->getArguments();
                foreach ($args as &$arg) {
                    $arg = $this->resolveParameter($arg, $registry);
                }
                $tag->setArguments($args);
            }

            // ThemeInfo
            if (null !== $info = $theme->getInformation()) {
                $info->setProperties($this->resolveParameterRecursive($info->getProperties(), $registry));
            }
        }
    }

    /**
     * Resolves a parameter value in a recursive way if present.
     *
     * @param mixed              $value     A value
     * @param ContainerInterface $container A container
     *
     * @return mixed
     *
     * @throws \RuntimeException When the given parameter does not exist
     */
    private function resolveParameterRecursive($value, ContainerInterface $container)
    {
        if (is_array($value)) {
            foreach ($value as &$child) {
                $child = $this->resolveParameterRecursive($child, $container);
            }

            return $value;
        }

        return $this->resolveParameter($value, $container);
    }

    /**
     * Resolves a parameter value if present.
     *
     * @param string             $value     A value
     * @param ContainerInterface $container A container
     *
     * @return mixed
     *
     * @throws \RuntimeException When the given parameter does not exist
     */
    private function resolveParameter($value, ContainerInterface $container)
    {
        if (!preg_match('/^%([^\s%]+)%$/', $value, $matches)) {
            return $value;
        }

        $paramName = $matches[1];
        if (!$container->hasParameter($paramName)) {
            throw new \RuntimeException(sprintf('The parameter "%s" can not be found.', $paramName));
        }

        return $container->getParameter($paramName);
    }
}
