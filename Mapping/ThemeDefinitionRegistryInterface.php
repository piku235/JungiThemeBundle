<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Mapping;

/**
 * ThemeDefinitionRegistryInterface.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface ThemeDefinitionRegistryInterface
{
    /**
     * Adds a theme definition.
     *
     * @param string          $name       A theme name
     * @param ThemeDefinition $definition A theme definition
     *
     * @throws \RuntimeException If there is a theme definition under the same name
     */
    public function registerThemeDefinition($name, ThemeDefinition $definition);

    /**
     * @param string $name A theme name
     *
     * @return bool
     */
    public function hasThemeDefinition($name);

    /**
     * Returns the given theme definition.
     *
     * @param string $name A theme name
     *
     * @return ThemeDefinition
     *
     * @throws \RuntimeException When the given theme definition does not exist
     */
    public function getThemeDefinition($name);

    /**
     * Returns the all registered theme definitions.
     *
     * @return ThemeDefinition[]
     */
    public function getThemeDefinitions();

    /**
     * Removes a theme definition.
     *
     * @param string $name A theme name
     */
    public function removeThemeDefinition($name);
}
