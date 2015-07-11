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
 * ThemeDefinitionRegistry.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeDefinitionRegistry implements ThemeDefinitionRegistryInterface
{
    /**
     * @var ThemeDefinition[]
     */
    protected $definitions = array();

    /**
     * {@inheritdoc}
     */
    public function registerThemeDefinition($name, ThemeDefinition $definition)
    {
        if ($this->hasThemeDefinition($name)) {
            throw new \RuntimeException(sprintf('There is already a theme definition under the name "%s".', $name));
        }

        $this->definitions[$name] = $definition;
    }

    /**
     * {@inheritdoc}
     */
    public function hasThemeDefinition($name)
    {
        return isset($this->definitions[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getThemeDefinition($name)
    {
        if (!isset($this->definitions[$name])) {
            throw new \RuntimeException(sprintf('The theme definition "%s" can not be found.', $name));
        }

        return $this->definitions[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function getThemeDefinitions()
    {
        return $this->definitions;
    }

    /**
     * {@inheritdoc}
     */
    public function removeThemeDefinition($name)
    {
        unset($this->definitions[$name]);
    }
}
