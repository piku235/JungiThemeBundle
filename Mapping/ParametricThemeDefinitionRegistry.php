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
 * ParametricThemeDefinitionRegistry.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ParametricThemeDefinitionRegistry extends ThemeDefinitionRegistry implements ParametricThemeDefinitionRegistryInterface
{
    /**
     * @var array
     */
    protected $parameters = array();

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
