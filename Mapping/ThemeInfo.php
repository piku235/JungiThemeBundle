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
 * ThemeInfo.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeInfo
{
    /**
     * @var array
     */
    private $properties = array();

    /**
     * Sets properties.
     *
     * @param array $properties Properties
     */
    public function setProperties(array $properties)
    {
        $this->properties = $properties;
    }

    /**
     * Sets a property.
     *
     * @param string $name  A name
     * @param mixed  $value A value
     */
    public function setProperty($name, $value)
    {
        $this->properties[$name] = $value;
    }

    /**
     * Checks if a given property exists.
     *
     * @param string $name A name
     *
     * @return bool
     */
    public function hasProperty($name)
    {
        return array_key_exists($name, $this->properties);
    }

    /**
     * Gets the property.
     *
     * @param string $name A name
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException If a property does not exist
     */
    public function getProperty($name)
    {
        if (!$this->hasProperty($name)) {
            throw new \InvalidArgumentException(sprintf('The property "%s" can not be found.', $name));
        }

        return $this->properties[$name];
    }

    /**
     * Returns the all properties.
     *
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }
}
