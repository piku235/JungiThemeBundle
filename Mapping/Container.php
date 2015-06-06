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
 * Container.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class Container implements ContainerInterface
{
    /**
     * @var ThemeDefinition[]
     */
    protected $themeDefinitions = array();

    /**
     * @var array
     */
    protected $parameters = array();

    /**
     * Sets a theme definition.
     *
     * @param string          $name       A theme name
     * @param ThemeDefinition $definition A theme definition
     *
     * @throws \RuntimeException If there is a theme definition under the same name
     */
    public function registerThemeDefinition($name, ThemeDefinition $definition)
    {
        if ($this->hasThemeDefinition($name)) {
            throw new \RuntimeException(sprintf('There is already registered theme definition under the name "%s".', $name));
        }

        $this->themeDefinitions[$name] = $definition;
    }

    /**
     * @param string $name A theme name
     *
     * @return bool
     */
    public function hasThemeDefinition($name)
    {
        return isset($this->themeDefinitions[$name]);
    }

    /**
     * Returns the given theme definition.
     *
     * @param string $name A theme name
     *
     * @return ThemeDefinition|null Null if the theme doesn't exist
     *
     * @throws \RuntimeException When the given theme definition does not exist
     */
    public function getThemeDefinition($name)
    {
        if (!isset($this->themeDefinitions[$name])) {
            throw new \RuntimeException(sprintf('The theme definition "%s" can not be found.', $name));
        }

        return $this->themeDefinitions[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function removeThemeDefinition($name)
    {
        unset($this->themeDefinitions[$name]);
    }

    /**
     * Returns the all registered theme definitions.
     *
     * @return ThemeDefinition[]
     */
    public function getThemeDefinitions()
    {
        return $this->themeDefinitions;
    }

    /**
     * Sets parameters.
     *
     * @param array $params Parameters
     */
    public function setParameters(array $params)
    {
        $this->parameters = $params;
    }

    /**
     * Sets a parameter.
     *
     * @param string $name  A name
     * @param mixed  $value A value
     */
    public function setParameter($name, $value)
    {
        $this->parameters[$name] = $value;
    }

    /**
     * Checks if a given parameter exists.
     *
     * @param string $name A name
     *
     * @return bool
     */
    public function hasParameter($name)
    {
        return array_key_exists($name, $this->parameters);
    }

    /**
     * Returns the parameter value.
     *
     * @param string $name A name
     *
     * @return mixed Null if it doesn't exist
     */
    public function getParameter($name)
    {
        if (!$this->hasParameter($name)) {
            return;
        }

        return $this->parameters[$name];
    }

    /**
     * Returns the all parameters.
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}
