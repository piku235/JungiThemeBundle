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

use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinition;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinitionRegistryInterface;
use Jungi\Bundle\ThemeBundle\Mapping\VirtualThemeDefinition;

/**
 * AbstractThemeWorker.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
abstract class AbstractThemeWorker implements WorkerInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ThemeDefinitionRegistryInterface $registry)
    {
        foreach ($registry->getThemeDefinitions() as $name => $theme) {
            $this->processTheme($name, $theme, $registry);

            if ($theme instanceof VirtualThemeDefinition) {
                foreach ($theme->getThemes() as $childName => $childTheme) {
                    $this->processTheme($childName, $childTheme, $registry);
                }
            }
        }
    }

    /**
     * Processes the given theme definition.
     *
     * @param string                           $name       A theme name
     * @param ThemeDefinition                  $definition A theme definition
     * @param ThemeDefinitionRegistryInterface $registry   A theme registry
     */
    abstract protected function processTheme($name, ThemeDefinition $definition, ThemeDefinitionRegistryInterface $registry);
}
